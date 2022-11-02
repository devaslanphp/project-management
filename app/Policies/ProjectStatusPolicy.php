<?php

namespace App\Policies;

use App\Models\ProjectStatus;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectStatusPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('List project statuses');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectStatus  $projectStatus
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ProjectStatus $projectStatus)
    {
        return $user->can('View project status');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('Create project status');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectStatus  $projectStatus
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ProjectStatus $projectStatus)
    {
        return $user->can('Update project status');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectStatus  $projectStatus
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ProjectStatus $projectStatus)
    {
        return $user->can('Delete project status');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectStatus  $projectStatus
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ProjectStatus $projectStatus)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectStatus  $projectStatus
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ProjectStatus $projectStatus)
    {
        //
    }
}
