<?php

namespace App\Policies;

use App\Models\TicketPriority;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPriorityPolicy
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
        return $user->can('List ticket priorities');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\TicketPriority $ticketPriority
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, TicketPriority $ticketPriority)
    {
        return $user->can('View ticket priority');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('Create ticket priority');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\TicketPriority $ticketPriority
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, TicketPriority $ticketPriority)
    {
        return $user->can('Update ticket priority');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\TicketPriority $ticketPriority
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, TicketPriority $ticketPriority)
    {
        return $user->can('Delete ticket priority');
    }
}
