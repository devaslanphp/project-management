<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Epic extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'project_id', 'starts_at', 'ends_at'
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'epic_id', 'id');
    }
}
