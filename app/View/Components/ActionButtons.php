<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ActionButtons extends Component
{
    public $routeName, $params, $restaurar, $showButton, $userId, $roleId;

    /**
     * @param string $routeName
     * @param object|null $params
     * @param bool $restaurar
     * @param bool $showButton
     */
    public function __construct(string $routeName = '', object $params = null, bool $restaurar = false,
                                bool   $showButton = false)
    {
        $this->routeName = $routeName;
        $this->params = $params;
        $this->restaurar = $restaurar;
        $this->showButton = $showButton;
        $this->userId = auth()->user()->id;
        $this->roleId = auth()->user()->roles->first()->id;
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
