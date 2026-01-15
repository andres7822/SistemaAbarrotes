<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMarcaRequest;
use App\Http\Requests\UpdateMarcaRequest;
use App\Models\Marca;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class marcaController extends actionPermissionController
{
    public function __construct()
    {
        parent::__construct('marca');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Marcas = Marca::all();
        return view('marca.index', compact('Marcas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('marca.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMarcaRequest $request)
    {
        try {
            DB::beginTransaction();
            Marca::create($request->all());
            $Mensaje = 'success__Agregado correctamente';
            DB::commit();
        } catch (Exception $e) {
            $Mensaje = 'error__' . $e->getMessage();
            DB::rollBack();
        }

        if ($request->accion == 'continuar') {
            return redirect()->route('marca.index')->with('mensaje', $Mensaje);
        }
        return redirect()->route('marca.create')->with('mensaje', $Mensaje);
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
    public function edit(Marca $Marca)
    {
        return view('marca.edit', compact('Marca'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMarcaRequest $request, Marca $Marca)
    {
        try {
            DB::beginTransaction();
            Marca::where('id', $Marca->id)
                ->update([
                    'nombre' => $request->nombre
                ]);
            $Mensaje = 'success__Actualizado correctamente';
            DB::commit();
        } catch (Exception $e) {
            $Mensaje = 'error__' . $e->getMessage();
            DB::rollBack();
        }
        return redirect()->route('marca.index')->with('mensaje', $Mensaje);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $Marca = Marca::find($id);
            if ($Marca->producto->count() < 1) {
                $Marca->delete();
                $Mensaje = 'success__Eliminado correctamente';
            } else if ($Marca->estado == 1) {
                $Marca->update(['estado' => 0]);
                $Mensaje = 'success__Se detectaron registrados asociados';
            } else {
                $Marca->update(['estado' => 1]);
                $Mensaje = 'success__Restaurado correctamente';
            }
            DB::commit();
        } catch (Exception $e) {
            $Mensaje = 'error__' . $e->getMessage();
            DB::rollBack();
        }
        return redirect()->route('categoria.index')->with('mensaje', $Mensaje);
    }
}
