<?php

namespace App\View\Components;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\View\Component;

class UserAvatar extends Component
{
    public Authenticatable|User $user;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Authenticatable|User $user)
    {
        $this->user = $user;
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
