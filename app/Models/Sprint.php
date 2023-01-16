<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sprint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'starts_at', 'ends_at', 'description',
        'project_id', 'started_at', 'ended_at'
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function (Sprint $item) {
            $epic = Epic::create([
                'name' => $item->name,
                'starts_at' => $item->starts_at,
                'ends_at' => $item->ends_at,
                'project_id' => $item->project_id
            ]);
            $item->epic_id = $epic->id;
            $item->save();
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'sprint_id', 'id');
    }

    public function epic(): BelongsTo
    {
        return $this->belongsTo(Epic::class, 'epic_id', 'id');
    }

    public function remaining(): Attribute
    {
        return new Attribute(
            get: function () {
                if ($this->starts_at && $this->ends_at && $this->started_at && !$this->ended_at) {
                    return $this->ends_at->diffInDays(now()) + 1;
                }
                return null;
            }
        );
    }
}
