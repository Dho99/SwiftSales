<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CreateButton extends Component
{
    /**
     * Create a new component instance.
     */
    public $beforeThisUrl;
    public $storeFormUrl;

    public function __construct($beforeThisUrl, $storeFormUrl)
    {
        $this->beforeThisUrl = $beforeThisUrl;
        $this->storeFormUrl = $storeFormUrl;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.create-button');
    }


}
