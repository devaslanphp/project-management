<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Project extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'name', 'description', 'status_id', 'owner_id'
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ProjectStatus::class, 'status_id', 'id')->withTrashed();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_users', 'project_id', 'user_id')->withPivot(['role']);
    }
}
