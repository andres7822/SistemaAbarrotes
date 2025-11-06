<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormButtons extends Component
{
    public $routeName, $isEdit;

    /**
     * @param string $routeName
     * @param bool $isEdit
     */
    public function __construct(string $routeName = '', bool $isEdit = false)
    {
        $this->routeName = $routeName;
        $this->isEdit = $isEdit;
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form-buttons');
    }
}
