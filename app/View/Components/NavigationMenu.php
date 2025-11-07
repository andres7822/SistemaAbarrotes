<?php

namespace App\View\Components;

use App\Models\Menu;
use Illuminate\View\Component;

class NavigationMenu extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $Menus = Menu::whereNull('menu_id')
            ->orderBy('tipo_menu_id')
            ->orderBy('nombre')
            ->with('subs')
            ->get();

        $MenuVista = '';

        foreach ($Menus as $Menu) {
            if ($Menu->tipo_menu_id == 1) {
                $MenuVista .= sprintf("
<a class='nav-link' href='%s'>
    <div class='sb-nav-link-icon'><i class='%s'></i></div>
    %s
</a>
                ", route($Menu->nombre_ruta . '.index'), $Menu->icono->nombre, $Menu->nombre);
            } else {
                $MenuVista .= sprintf("
<a class='nav-link collapsed' href='#' data-bs-toggle='collapse' data-bs-target='#collapse%s'
    aria-expanded='false' aria-controls='collapse%s'>
    <div class='sb-nav-link-icon'><i class='%s'></i></div>
    %s
    <div class='sb-sidenav-collapse-arrow'><i class='fas fa-angle-down'></i></div>
</a>
<div class='collapse' id='collapse%s' aria-labelledby='headingTwo'
    data-bs-parent='#sidenavAccordion'>
    <nav class='sb-sidenav-menu-nested nav accordion' id='sidenavAccordion%s'>
    <!--#OPCIONES#-->
    </nav>
</div>
                ", str_replace(' ', '', $Menu->nombre), str_replace(' ', '', $Menu->nombre),
                    $Menu->icono->nombre, $Menu->nombre, str_replace(' ', '', $Menu->nombre),
                    str_replace(' ', '', $Menu->nombre)
                );

                $MenuVista = str_replace('<!--#OPCIONES#-->', $this->submenus($Menu), $MenuVista);
            }
        }

        return view('components.navigation-menu', compact('MenuVista'));
    }

    public function submenus($Menu)
    {
        $Opciones = '';

        foreach ($Menu->subs as $index => $item) {
            if ($item->tipo_menu_id == 3) {
                $Opciones .= sprintf("
<a class='nav-link' href='%s'>%s</a>
        ", route($item->nombre_ruta . '.index'), $item->nombre);
            } else {

                $Opciones .= sprintf("
<a class='nav-link collapsed' href='#' data-bs-toggle='collapse'
    data-bs-target='#%sCollapse%s' aria-expanded='false'
    aria-controls='%sCollapse%s'>
    <i class='%s'></i>&nbsp;%s
    <div class='sb-sidenav-collapse-arrow'><i class='fas fa-angle-down'></i></div>
</a>
<div class='collapse' id='%sCollapse%s' aria-labelledby='headingOne'
    data-bs-parent='sidenavAccordion%s'>
    <nav class='sb-sidenav-menu-nested nav'>
        <a class='nav-link' href='login.html'>Login</a>
    </nav>
</div>
                ", str_replace(' ', '', $Menu->nombre), str_replace(' ', '', $item->nombre),
                    str_replace(' ', '', $Menu->nombre), str_replace(' ', '', $item->nombre),
                    $item->icono->nombre, $item->nombre,
                    str_replace(' ', '', $Menu->nombre), str_replace(' ', '', $item->nombre),
                    $Menu->nombre
                );
            }
        }

        return $Opciones;
    }
}
