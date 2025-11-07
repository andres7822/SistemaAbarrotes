<?php

namespace App\Http\Controllers;

use App\Models\Icono;
use App\Models\Menu;
use App\Models\TipoMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class menuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $Menus = Menu::whereNull('menu_id')
            ->orderBy('tipo_menu_id')
            ->orderBy('nombre')
            ->with('subs')
            ->get();
        return $Menus;

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
<a class='nav-link collapsed' href='#' data-bs-toggle='collapse' data-bs-target='collapse%s'
    aria-expanded='false' aria-controls='collapse%s'>
    <div class='sb-nav-link-icon'><i class='%s'></i></div>
    %s
    <div class='sb-sidenav-collapse-arrow'><i class='fas fa-angle-down'></i></div>
</a>
<div class='collpase' id='collapse%s' aria-labelledby='headingOne'
    data-bs-parent='#sidenavAccordion'>
    <nav class='sb-sidenav-menu-nested nav'>
    <!--#OPCIONES#-->
    </nav>
</div>
                ", str_replace(' ', '', $Menu->nombre), str_replace(' ', '', $Menu->nombre),
                    $Menu->icono->nombre, $Menu->nombre, str_replace(' ', '', $Menu->nombre));
                return $this->submenus($Menu);
                $MenuVista = str_replace('<!--#OPCIONES-->', 2, $MenuVista);
            }
        }

        //$Menus = Menu::all();
        //return view('menu.index', compact('Menus'));
    }

    public function submenus($Menu)
    {
        $Opciones = '';

        foreach ($Menu->subs as $index => $item) {
            if ($item->tipo_menu_id == 4) {
                $Opciones .= sprintf("
<a class='nav-link' href='%s'>%s</a>
        ", route($Menu->nombre_ruta . '.index'), $Menu->nombre);
            }
        }

        return $Opciones;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $Iconos = Icono::all();
        $TipoMenus = TipoMenu::all();
        $Menus = Menu::whereIn('tipo_menu_id', [2, 4])
            ->get();

        return view('menu.create', compact('Iconos', 'TipoMenus', 'Menus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $requestArray = [
            'nombre' => 'required|max:128|unique:menus,nombre',
            'icono_id' => 'required|exists:iconos,id',
            'tipo_menu_id' => 'required|exists:tipo_menus,id'
        ];

        if (in_array($request->tipo_menu_id, [3, 4])) {
            $requestArray['menu_id'] = 'required|exists:menus,id';
        }

        if (in_array($request->tipo_menu_id, [1, 3])) {
            $requestArray['nombre_ruta'] = 'required|max:32|unique:menus,nombre_ruta';
        }

        $request->validate($requestArray);

        try {
            DB::beginTransaction();
            Menu::create($request->all());
            $Mensaje = 'success__Agregado correctamente';
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $Mensaje = 'error__' . $e->getMessage();
        }

        if ($request->accion == 'continuar') {
            return redirect()->route('menu.index')->with('mensaje', $Mensaje);
        }
        return redirect()->route('menu.create')->with('mensaje', $Mensaje);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $Menu)
    {
        $Iconos = Icono::all();

        $TipoMenus = TipoMenu::all();

        $Menus = Menu::all();

        return view('menu.edit', compact('Iconos', 'TipoMenus', 'Menus', 'Menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $Menu)
    {
        $requestArray = [
            'nombre' => 'required|max:128|unique:menus,nombre,' . $Menu->id,
            'icono_id' => 'required|exists:iconos,id',
            'tipo_menu_id' => 'required|exists:tipo_menus,id'
        ];

        if ($request->tipo_menu_id == 3) {
            $requestArray['menu_id'] = 'required|exists:menus,id';
        }

        $request->validate($requestArray);

        try {
            DB::beginTransaction();
            $Menu->where('id', $Menu->id)
                ->update($request->except('_method', '_token'));
            $Mensaje = 'success__Actualizado correctamente';
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $Mensaje = 'error__' . $e->getMessage();
        }
        return redirect()->route('menu.index')->with('mensaje', $Mensaje);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $Menu = Menu::find($id);
            $Menu->delete();
            $Mensaje = 'success__Eliminado correctamente';
            DB::commit();
        } catch (\Exception $e) {
            $Mensaje = 'error__' . $e->getMessage();
            DB::rollBack();
        }
        return redirect()->route('menu.index')->with('mensaje', $Mensaje);
    }
}
