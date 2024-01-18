<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use stdClass;

class categorizableCardComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public stdClass $category,
        public stdClass $event 
    )
    {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.categorizable-card-component');
    }
}
