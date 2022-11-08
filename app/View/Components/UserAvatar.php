<?php

namespace App\View\Components;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\View\Component;

class UserAvatar extends Component
{
    public Authenticatable|User $user;
    public int $tickets;
    public int $projects;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Authenticatable|User $user)
    {
        $this->user = $user;
        $this->tickets = collect($user->ticketsOwned->merge($user->ticketsResponsible))->unique('id')->count();
        $this->projects = collect($user->projectsOwning->merge($user->projectsAffected))->unique('id')->count();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.user-avatar');
    }
}
