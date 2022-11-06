<?php

namespace App\View\Components;

use App\Models\TicketPriority as Model;
use Illuminate\View\Component;

class TicketPriority extends Component
{
    public Model $priority;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Model $priority)
    {
        $this->priority = $priority;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.ticket-priority');
    }
}
