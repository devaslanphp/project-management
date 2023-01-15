<?php

namespace App\Models;

use App\Notifications\TicketCreated;
use App\Notifications\TicketStatusUpdated;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ticket extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'name', 'content', 'owner_id', 'responsible_id',
        'status_id', 'project_id', 'code', 'order', 'type_id',
        'priority_id', 'estimation', 'epic_id', 'sprint_id'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function (Ticket $item) {
            $project = Project::where('id', $item->project_id)->first();
            $count = Ticket::where('project_id', $project->id)->count();
            $order = $project->tickets?->last()?->order ?? -1;
            $item->code = $project->ticket_prefix . '-' . ($count + 1);
            $item->order = $order + 1;
        });

        static::created(function (Ticket $item) {
            if ($item->sprint_id && $item->sprint->epic_id) {
                Ticket::where('id', $item->id)->update(['epic_id' => $item->sprint->epic_id]);
            }
            foreach ($item->watchers as $user) {
                $user->notify(new TicketCreated($item));
            }
        });

        static::updating(function (Ticket $item) {
            $old = Ticket::where('id', $item->id)->first();

            // Ticket activity based on status
            $oldStatus = $old->status_id;
            if ($oldStatus != $item->status_id) {
                TicketActivity::create([
                    'ticket_id' => $item->id,
                    'old_status_id' => $oldStatus,
                    'new_status_id' => $item->status_id,
                    'user_id' => auth()->user()->id
                ]);
                foreach ($item->watchers as $user) {
                    $user->notify(new TicketStatusUpdated($item));
                }
            }

            // Ticket sprint update
            $oldSprint = $old->sprint_id;
            if ($oldSprint && !$item->sprint_id) {
                Ticket::where('id', $item->id)->update(['epic_id' => null]);
            } elseif ($item->sprint_id && $item->sprint->epic_id) {
                Ticket::where('id', $item->id)->update(['epic_id' => $item->sprint->epic_id]);
            }
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_id', 'id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class, 'status_id', 'id')->withTrashed();
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id')->withTrashed();
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TicketType::class, 'type_id', 'id')->withTrashed();
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(TicketPriority::class, 'priority_id', 'id')->withTrashed();
    }

    public function activities(): HasMany
    {
        return $this->hasMany(TicketActivity::class, 'ticket_id', 'id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class, 'ticket_id', 'id');
    }

    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ticket_subscribers', 'ticket_id', 'user_id');
    }

    public function relations(): HasMany
    {
        return $this->hasMany(TicketRelation::class, 'ticket_id', 'id');
    }

    public function hours(): HasMany
    {
        return $this->hasMany(TicketHour::class, 'ticket_id', 'id');
    }

    public function epic(): BelongsTo
    {
        return $this->belongsTo(Epic::class, 'epic_id', 'id');
    }

    public function sprint(): BelongsTo
    {
        return $this->belongsTo(Sprint::class, 'sprint_id', 'id');
    }

    public function sprints(): BelongsTo
    {
        return $this->belongsTo(Sprint::class, 'sprint_id', 'id');
    }

    public function watchers(): Attribute
    {
        return new Attribute(
            get: function () {
                $users = $this->project->users;
                $users->push($this->owner);
                if ($this->responsible) {
                    $users->push($this->responsible);
                }
                return $users->unique('id');
            }
        );
    }

    public function totalLoggedHours(): Attribute
    {
        return new Attribute(
            get: function () {
                $seconds = $this->hours->sum('value') * 3600;
                return CarbonInterval::seconds($seconds)->cascade()->forHumans();
            }
        );
    }

    public function totalLoggedSeconds(): Attribute
    {
        return new Attribute(
            get: function () {
                return $this->hours->sum('value') * 3600;
            }
        );
    }

    public function totalLoggedInHours(): Attribute
    {
        return new Attribute(
            get: function () {
                return $this->hours->sum('value');
            }
        );
    }

    public function estimationForHumans(): Attribute
    {
        return new Attribute(
            get: function () {
                return CarbonInterval::seconds($this->estimationInSeconds)->cascade()->forHumans();
            }
        );
    }

    public function estimationInSeconds(): Attribute
    {
        return new Attribute(
            get: function () {
                if (!$this->estimation) {
                    return null;
                }
                return $this->estimation * 3600;
            }
        );
    }

    public function estimationProgress(): Attribute
    {
        return new Attribute(
            get: function () {
                return (($this->totalLoggedSeconds ?? 0) / ($this->estimationInSeconds ?? 1)) * 100;
            }
        );
    }

    public function completudePercentage(): Attribute
    {
        return new Attribute(
            get: fn() => $this->estimationProgress
        );
    }
}
