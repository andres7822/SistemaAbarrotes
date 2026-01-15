<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTiendaRequest;
use App\Http\Requests\UpdateTiendaRequest;
use App\Models\Tienda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class tiendaController extends actionPermissionController
{
    public function __construct()
    {
        parent::__construct('tienda');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Tiendas = Tienda::orderBy('estado', 'desc')
            ->get();
        return view('tienda.index', compact('Tiendas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tienda.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTiendaRequest $request)
    {
        try {
            DB::beginTransaction();
            $Tienda = Tienda::create($request->all());
            $this->register->registro('tiendas', $Tienda->id, 4, $Tienda->toArray());
            $Mensaje = 'success__Agregado correctamente';
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $Mensaje = 'error__' . $e->getMessage();
            return redirect()->route('tienda.create')->with('mensaje', $Mensaje)->withInput();
        }

        if ($request->accion == 'continuar') {
            return redirect()->route('tienda.index')->with('mensaje', $Mensaje);
        }
        return redirect()->route('tienda.create')->with('mensaje', $Mensaje);
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
    public function edit(Tienda $Tienda)
    {
        return view('tienda.edit', compact('Tienda'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTiendaRequest $request, Tienda $Tienda)
    {
        try {
            DB::beginTransaction();
            $Tienda->update($request->except('_token', '_method'));
            $this->register->registro('tiendas', $Tienda->id, 5, $Tienda->toArray());
            $Mensaje = 'success__Actualizado correctamente';
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $Mensaje = 'error__' . $e->getMessage();
            return redirect()->route('tienda.edit')->with('mensaje', $Mensaje)->withInput();
        }
        return redirect()->route('tienda.index')->with('mensaje', $Mensaje);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $Tienda = Tienda::find($id);
            if ($Tienda->bodega->count() < 1) {
                $Tienda->delete();
                $Mensaje = 'success__Eliminado correctamente';
                $this->register->registro('tiendas', $Tienda->id, 6, $Tienda->toArray());
            } else if ($Tienda->estado == 1) {
                Tienda::where('id', $id)
                    ->update(['estado' => 0]);
                $Mensaje = 'success__Se detectaron registros asociados';
                $this->register->registro('tiendas', $Tienda->id, 5, ['estado' => 0]);
            } else {
                Tienda::where('id', $id)
                    ->update(['estado' => 1]);
                $this->register->registro('tiendas', $Tienda->id, 5, ['estado' => 1]);
                $Mensaje = 'success__Restaurado correctamente';
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $Mensaje = 'error__' . $e->getMessage();
        }
        return redirect()->route('tienda.index')->with('mensaje', $Mensaje);
    }
}
