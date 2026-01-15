<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Cliente;
use App\Models\Sexo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class clienteController extends actionPermissionController
{
    public function __construct()
    {
        parent::__construct('cliente');
    }

    public function index()
    {
        $Clientes = Cliente::all();
        return view('cliente.index', compact('Clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $Sexos = Sexo::all();
        return view('cliente.create', compact('Sexos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClienteRequest $request)
    {
        try {
            DB::beginTransaction();
            $Cliente = Cliente::create($request->all());
            $Mensaje = 'success__Agregado Correctamente';
            $this->register->registro('clientes', $Cliente->id, '4', $Cliente->toArray());
            DB::commit();
        } catch (\Exception $e) {
            $Mensaje = 'error__' . $e->getMessage();
            DB::rollBack();
            return redirect()->route('cliente.create')->with('mensaje', $Mensaje);
        }
        if ($request->accion == 'continuar') {
            return redirect()->route('cliente.index')->with('mensaje', $Mensaje);
        }
        return redirect()->route('cliente.create')->with('mensaje', $Mensaje);
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
    public function edit(Cliente $Cliente)
    {
        $Sexos = Sexo::all();
        return view('cliente.edit', compact('Sexos', 'Cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClienteRequest $request, Cliente $Cliente)
    {
        try {
            DB::beginTransaction();
            $Cliente->update($request->except('_token', '_method'));
            $Mensaje = 'success__Actualizado correctamente';
            DB::commit();
        } catch (\Exception $e) {
            $Mensaje = 'error__' . $e->getMessage();
            DB::rollBack();
        }
        return redirect()->route('cliente.index')->with('mensaje', $Mensaje);
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
