<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\TypePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class roleController extends actionPermissionController
{
    public function __construct()
    {
        parent::__construct('role');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Roles = Role::all();
        return view('role.index', compact('Roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $Menus = Menu::whereIn('tipo_menu_id', [1, 3])
            ->orderBy('nombre')
            ->get();

        $TypePermissions = TypePermission::all();

        return view('role.create', compact('Menus', 'TypePermissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $requestArray = [
            'name' => 'required|max:255|unique:roles,name'
        ];

        if (!isset($request->permiso)) {
            $requestArray['permiso'] = 'required';
        } else {
            $requestArray['permiso.*'] = 'required';
        }

        $request->validate($requestArray);

        $ids = [];

        foreach ($request->permiso as $perm) {
            // Separar el valor "5_3" en $menu_id = 5 y $type_permission_id = 3
            [$menu_id, $type_permission_id] = explode('_', $perm);

            // Buscar el ID del permiso que coincida con ambos campos
            $permission = DB::table('permissions')
                ->where('menu_id', $menu_id)
                ->where('type_permission_id', $type_permission_id)
                ->value('id'); // Obtiene solo el ID

            if ($permission) {
                $ids[] = $permission;
            }
        }

        try {
            DB::beginTransaction();
            $rol = Role::create([
                'name' => $request->name
            ]);
            $rol->syncPermissions($ids);
            $Mensaje = 'success__Agregado correctamente';
            DB::commit();
        } catch (\Exception $e) {
            $Mensaje = 'error__' . $e->getMessage();
            DB::rollBack();
            return redirect()->route('role.create')->with('mensaje', $Mensaje);
        }

        if ($request->accion = 'continuar') {
            return redirect()->route('role.index')->with('mensaje', $Mensaje);
        }
        return redirect()->route('role.create')->with('mensaje', $Mensaje);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $Role)
    {
        $Menus = Menu::whereIn('tipo_menu_id', [1, 3])
            ->orderBy('nombre')
            ->get();

        $TypePermissions = TypePermission::all();

        $idPermissions = $Role->permissions->pluck('id')->toArray();
        $Permisos = Permission::select(DB::raw("CONCAT_WS('_', menu_id, type_permission_id) as menu_type"))
            ->whereIn('id', $idPermissions)
            ->get()
            ->pluck('menu_type')
            ->toArray();

        return view('role.edit', compact('Menus', 'TypePermissions', 'Role', 'Permisos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $requestArray = [
            'name' => 'required|max:255|unique:roles,name,' . $role->id
        ];

        if (!isset($request->permiso)) {
            $requestArray['permiso'] = 'required';
        } else {
            $requestArray['permiso.*'] = 'required';
        }

        $request->validate($requestArray);

        $ids = [];

        foreach ($request->permiso as $perm) {
            // Separar el valor "5_3" en $menu_id = 5 y $type_permission_id = 3
            [$menu_id, $type_permission_id] = explode('_', $perm);

            // Buscar el ID del permiso que coincida con ambos campos
            $permission = DB::table('permissions')
                ->where('menu_id', $menu_id)
                ->where('type_permission_id', $type_permission_id)
                ->value('id'); // Obtiene solo el ID

            if ($permission) {
                $ids[] = $permission;
            }
        }

        try {
            DB::beginTransaction();

            Role::where('id', $role->id)
                ->update([
                    'name' => $request->name
                ]);

            $role->syncPermissions($ids);
            $Mensaje = 'success__Actalizado correctamente';
            DB::commit();
        } catch (\Exception $e) {
            $Mensaje = 'error__' . $e->getMessage();
            DB::rollBack();
            return redirect()->route('role.create')->with('mensaje', $Mensaje);
        }
        return redirect()->route('role.index')->with('mensaje', $Mensaje);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
