<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TimeInputComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public string $inputName, public int $hours = 0, public int $minutes = 0)
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.time-input-component');
    }
}
