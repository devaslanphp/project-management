<?php

namespace App\Policies;

use App\Models\Sprint;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SprintPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('List sprints');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Sprint $sprint
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Sprint $sprint)
    {
        return $user->can('View sprint')
            && (
                $sprint->project->owner_id === $user->id
                ||
                $sprint->project->users()->where('users.id', $user->id)->count()
            );
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('Create sprint');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Sprint $sprint
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Sprint $sprint)
    {
        return $user->can('Update sprint')
            && (
                $sprint->project->owner_id === $user->id
                ||
                $sprint->project->users()->where('users.id', $user->id)
                    ->where('role', config('system.projects.affectations.roles.can_manage'))
                    ->count()
            );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Sprint $sprint
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Sprint $sprint)
    {
        return $user->can('Delete sprint');
    }
}
