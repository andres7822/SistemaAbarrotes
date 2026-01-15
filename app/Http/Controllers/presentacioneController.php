<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePresentacioneRequest;
use App\Http\Requests\UpdatePresentacioneRequest;
use App\Models\Presentacione;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class presentacioneController extends actionPermissionController
{
    public function __construct()
    {
        parent::__construct('presentacione');
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Presentaciones = Presentacione::all();
        return view('presentacione.index', compact('Presentaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('presentacione.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePresentacioneRequest $request)
    {
        try {
            DB::beginTransaction();
            Presentacione::create($request->all());
            $Mensaje = 'success__Agregado correctamente';
            DB::commit();
        } catch (Exception $e) {
            $Mensaje = 'error__' . $e->getMessage();
            DB::rollBack();
        }

        if ($request->accion == 'continuar') {
            return redirect()->route('presentacione.index')->with('mensaje', $Mensaje);
        }
        return redirect()->route('presentacione.create')->with('mensaje', $Mensaje);
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
    public function edit(Presentacione $Presentacione)
    {
        return view('presentacione.edit', compact('Presentacione'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePresentacioneRequest $request, Presentacione $Presentacione)
    {
        try {
            DB::beginTransaction();
            Presentacione::where('id', $Presentacione->id)
                ->update([
                    'nombre' => $request->nombre
                ]);
            $Mensaje = 'success__Actualizado correctamente';
            DB::commit();
        } catch (Exception $e) {
            $Mensaje = 'error__' . $e->getMessage();
            DB::rollBack();
        }
        return redirect()->route('presentacione.index')->with('mensaje', $Mensaje);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $Presentacione = Presentacione::find($id);
            if ($Presentacione->subpresentacione->count() < 1) {
                $Presentacione->delete();
                $Mensaje = 'success__Eliminado correctamente';
            } else if ($Presentacione->estado == 1) {
                $Presentacione->update(['estado' => 0]);
                $Mensaje = 'success__Se detectaron registrados asociados';
            } else {
                $Presentacione->update(['estado' => 1]);
                $Mensaje = 'success__Restaurado correctamente';
            }
            DB::commit();
        } catch (Exception $e) {
            $Mensaje = 'error__' . $e->getMessage();
            DB::rollBack();
        }
        return redirect()->route('presentacione.index')->with('mensaje', $Mensaje);
    }
}
