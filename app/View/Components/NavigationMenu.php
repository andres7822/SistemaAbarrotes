<?php

namespace App\View\Components;

use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
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
        $role_id = auth()->user()->roles->first()->id;

        $results = DB::table('role_has_permissions as rp')
            ->join('permissions as p', 'rp.permission_id', '=', 'p.id')
            ->join('menus as m', 'p.menu_id', '=', 'm.id')
            ->where('rp.role_id', $role_id)
            ->groupBy('p.menu_id', 'm.id', 'm.nombre', 'm.menu_id')  // Añadir m.menu_id al GROUP BY
            ->select('m.id', 'm.nombre', 'm.menu_id')
            ->get()
            ->toArray();

        $idVistas = array_column($results, 'id');
        $idMenus = array_column($results, 'menu_id');

        $Menus = Menu::whereNull('menu_id')
            ->orderBy('tipo_menu_id')
            ->orderBy('prioridad')
            ->orderBy('nombre')
            ->with('subs')
            ->get();

        $MenuVista = '';

        foreach ($Menus as $Menu) {
            if ($Menu->tipo_menu_id == 1) {
                $NombreRuta = Route::has($Menu->nombre_ruta . '.index') ? $Menu->nombre_ruta . '.index' : 'home';
                if ($role_id == 1) {
                    $MenuVista .= sprintf("
<a class='nav-link' href='%s'>
    <div class='sb-nav-link-icon'><i class='%s'></i></div>
    %s
</a>
                ", route($NombreRuta), $Menu->icono->nombre, $Menu->nombre);
                } else if (in_array($Menu->id, $idVistas)) {
                    $MenuVista .= sprintf("
<a class='nav-link' href='%s'>
    <div class='sb-nav-link-icon'><i class='%s'></i></div>
    %s
</a>
                ", route($NombreRuta), $Menu->icono->nombre, $Menu->nombre);
                }
            } else {
                if ($role_id == 1) {
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
                    $MenuVista = str_replace('<!--#OPCIONES#-->', $this->submenus($Menu, $idVistas, $idMenus, $role_id), $MenuVista);
                } else if (in_array($Menu->id, $idMenus)) {
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
                    $MenuVista = str_replace('<!--#OPCIONES#-->', $this->submenus($Menu, $idVistas, $idMenus, $role_id), $MenuVista);
                }
            }
        }

        return view('components.navigation-menu', compact('MenuVista'));
    }

    public function submenus($Menu, $idVistas, $idMenus, $role_id)
    {
        $Opciones = '';

        foreach ($Menu->subs as $index => $item) {
            if ($item->tipo_menu_id == 3) { //SUBVISTA
                $NombreRuta = Route::has($item->nombre_ruta . '.index') ? $item->nombre_ruta . '.index' : 'home';
                if ($role_id == 1) {
                    $Opciones .= sprintf("
<a class='nav-link' href='%s'><i class='%s'></i>&nbsp;%s</a>
        ", route($NombreRuta), $item->icono->nombre, $item->nombre);
                } else if (in_array($item->id, $idVistas)) {
                    $Opciones .= sprintf("
<a class='nav-link' href='%s'><i class='%s'></i>&nbsp;%s</a>
        ", route($NombreRuta), $item->icono->nombre, $item->nombre);
                }
            } else { //SUBMENU
                if ($role_id == 1) {
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
        <!--#OPCIONES#-->
    </nav>
</div>
                ", str_replace(' ', '', $Menu->nombre), str_replace(' ', '', $item->nombre),
                        str_replace(' ', '', $Menu->nombre), str_replace(' ', '', $item->nombre),
                        $item->icono->nombre, $item->nombre,
                        str_replace(' ', '', $Menu->nombre), str_replace(' ', '', $item->nombre),
                        $Menu->nombre
                    );
                    $Opciones = str_replace('<!--#OPCIONES#-->', $this->submenus($item, $idVistas, $idMenus, $role_id), $Opciones);
                } else if (in_array($item->id, $idMenus)) {
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
        <!--#OPCIONES#-->
    </nav>
</div>
                ", str_replace(' ', '', $Menu->nombre), str_replace(' ', '', $item->nombre),
                        str_replace(' ', '', $Menu->nombre), str_replace(' ', '', $item->nombre),
                        $item->icono->nombre, $item->nombre,
                        str_replace(' ', '', $Menu->nombre), str_replace(' ', '', $item->nombre),
                        $Menu->nombre
                    );
                    $Opciones = str_replace('<!--#OPCIONES#-->', $this->submenus($item, $idVistas, $idMenus, $role_id), $Opciones);
                }
            }
        }

        return $Opciones;
    }
}
