<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class userController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']); // superadmin primero

        // Luego vienen los permisos
        $this->middleware('permission:ver-user|crear-user|editar-user|eliminar-user', ['only' => ['index', 'show']]);
        $this->middleware('permission:crear-user', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-user', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-user', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('estado', 'desc')
            ->orderBy('name')
            ->get();

        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $Roles = Role::all();
        return view('user.create', compact('Roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();

            //Encriptar la contraseña
            //$fieldHash = Hash::make($request->password);
            $fieldHash = bcrypt($request->password);
            //Modificar el valor de password en nuetro request
            $request['password'] = $fieldHash;

            $Usuario = User::create($request->all());

            //Asignar su rol
            $Usuario->assignRole($request->role);

            $Mensaje = 'success__Agregado correctamente';
            DB::commit();
        } catch (\Exception $e) {
            $Mensaje = 'error__' . $e->getMessage();
            DB::rollBack();
            return redirect()->route('user.create')->with('mensaje', $Mensaje);
        }

        if ($request->accion == 'continuar') {
            return redirect()->route('user.index')->with('mensaje', $Mensaje);
        }
        return redirect()->route('user.create')->with('mensaje', $Mensaje);
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
    public function edit(User $user)
    {
        $Roles = Role::all();
        return view('user.edit', compact('user', 'Roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            DB::beginTransaction();

            //Comprobar el password y aplicar el hash
            if (empty($request->password)) {
                $request = Arr::except($request, array('password'));
            } else {
                //Encriptar contraseña
                $fieldHash = Hash::make($request->password);
                //Modificar el valor de password en nuestro request
                $request->merge(['password' => $fieldHash]);
            }

            //Actualizar usuario
            $user->update($request->all());

            //Actualizar su rol
            $user->syncRoles([$request->role]);

            DB::commit();
            $Mensaje = 'success__Actualizado Correctamente';
        } catch (\Exception $e) {
            DB::rollBack();
            $Mensaje = 'error__' . $e->getMessage();
        }
        return redirect()->route('user.index')->with('mensaje', $Mensaje);
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
