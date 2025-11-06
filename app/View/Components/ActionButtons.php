<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ActionButtons extends Component
{
    public $routeName, $params, $restaurar, $showButton;

    /**
     * @param string $routeName
     * @param object|null $params
     * @param bool $restaurar
     * @param bool $showButton
     */
    public function __construct(string $routeName = '', object $params = null, bool $restaurar = false, bool $showButton = false)
    {
        $this->routeName = $routeName;
        $this->params = $params;
        $this->restaurar = $restaurar;
        $this->showButton = $showButton;
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.action-buttons');
    }
}
