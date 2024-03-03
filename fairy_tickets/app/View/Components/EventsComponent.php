<?php

namespace App\View\Components;

use Closure;
use App\Models\Event;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use stdClass;

class EventsComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Event $event
    )
    {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.events-component');
    }
}
