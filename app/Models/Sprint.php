<?php

namespace App\Models;

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
        'project_id'
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
}
