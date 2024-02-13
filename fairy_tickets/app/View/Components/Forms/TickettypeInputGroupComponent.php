<?php

namespace App\View\Components\Forms;

use Closure;
use App\Models\TicketType;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class TickettypeInputGroupComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public int $index,
        public ?TicketType $ticketType,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.tickettype-input-group-component');
    }
}
