<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ActionButtonsHead extends Component
{
    public $routeName, $pdfFile, $pdfRoute, $pdfButtonName, $userId, $roleId;

    /**
     * @param string $routeName
     */
    public function __construct(string $routeName = '', bool $pdfFile = false, string $pdfRoute = '',
                                string $pdfButtonName = 'Generar PDF')
    {
        $this->routeName = $routeName;
        $this->pdfFile = $pdfFile;
        $this->pdfRoute = $pdfRoute == '' ? $routeName . '.reporte' : $pdfRoute;
        $this->pdfButtonName = $pdfButtonName;
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
        return view('components.action-buttons-head');
    }
}
