<?php

namespace App\View\Components;

use Closure;
use stdClass;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class OpinionCardComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public stdClass $opinion)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.opinion-card-component');
    }
}
