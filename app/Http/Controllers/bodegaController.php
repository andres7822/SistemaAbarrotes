<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBodegaRequest;
use App\Http\Requests\UpdateBodegaRequest;
use App\Models\Bodega;
use App\Models\Tienda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class bodegaController extends actionPermissionController
{
    public function __construct()
    {
        parent::__construct('bodega');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Bodegas = Bodega::orderBy('estado', 'desc')
            ->get();
        return view('bodega.index', compact('Bodegas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $Tiendas = Tienda::where('estado', 1)
            ->orderBy('nombre')
            ->get();
        return view('bodega.create', compact('Tiendas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBodegaRequest $request)
    {
        try {
            DB::beginTransaction();
            $Bodega = Bodega::create($request->all());
            $Mensaje = 'success__Agregado correctamente';
            $this->register->registro('bodegas', $Bodega->id, 4, $Bodega->toArray());
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $Mensaje = 'error__' . $e->getMessage();
            return redirect()->route('bodega.create')->with('mensaje', $Mensaje)->withInput();
        }

        if ($request->accion == 'continuar') {
            return redirect()->route('bodega.index')->with('mensaje', $Mensaje);
        }
        return redirect()->route('bodega.create')->with('mensaje', $Mensaje);
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
    public function edit(Bodega $Bodega)
    {
        $Tiendas = Tienda::where('estado', 1)
            ->orderBy('nombre')
            ->get();
        return view('bodega.edit', compact('Tiendas', 'Bodega'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBodegaRequest $request, Bodega $Bodega)
    {
        try {
            DB::beginTransaction();
            $Bodega->update($request->except('_token', '_method'));
            $this->register->registro('bodegas', $Bodega->id, 5, $Bodega->toArray());
            $Mensaje = 'success__Actualizado correctamente';
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $Mensaje = 'error__' . $e->getMessage();
            return redirect()->route('bodega.edit')->with('mensaje', $Mensaje)->withInput();
        }
        return redirect()->route('bodega.index')->with('mensaje', $Mensaje);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $Bodega = Bodega::find($id);
            if ($Bodega->inventario->count() < 1) {
                $Bodega->delete();
                $Mensaje = 'success__Eliminado correctamente';
                $this->register->registro('bodegas', $Bodega->id, 6, $Bodega->toArray());
            } else if ($Bodega->estado == 1) {
                Bodega::where('id', $id)
                    ->update(['estado' => 0]);
                $Mensaje = 'success__Se detectaron registros asociados';
                $this->register->registro('bodegas', $Bodega->id, 5, ['estado' => 0]);
            } else {
                Bodega::where('id', $id)
                    ->update(['estado' => 1]);
                $this->register->registro('bodegas', $Bodega->id, 5, ['estado' => 1]);
                $Mensaje = 'success__Restaurado correctamente';
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $Mensaje = 'error__' . $e->getMessage();
        }
        return redirect()->route('bodega.index')->with('mensaje', $Mensaje);
    }
}
