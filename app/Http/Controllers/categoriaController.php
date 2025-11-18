<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Models\Categoria;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class categoriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver-categoria|crear-categoria|editar-categoria|eliminar-categoria', ['only' => ['index', 'show']]);
        $this->middleware('permission:crear-categoria', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-categoria', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-categoria', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Categorias = Categoria::all();
        return view('categoria.index', compact('Categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categoria.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoriaRequest $request)
    {
        try {
            DB::beginTransaction();
            Categoria::create($request->all());
            $Mensaje = 'success__Agregado correctamente';
            DB::commit();
        } catch (Exception $e) {
            $Mensaje = 'error__' . $e->getMessage();
            DB::rollBack();
        }

        if ($request->accion == 'continuar') {
            return redirect()->route('categoria.index')->with('mensaje', $Mensaje);
        }
        return redirect()->route('categoria.create')->with('mensaje', $Mensaje);
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
    public function edit(Categoria $Categoria)
    {
        return view('categoria.edit', compact('Categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoriaRequest $request, Categoria $Categoria)
    {
        try {
            DB::beginTransaction();
            Categoria::where('id', $Categoria->id)
                ->update([
                    'nombre' => $request->nombre
                ]);
            $Mensaje = 'success__Actualizado correctamente';
            DB::commit();
        } catch (Exception $e) {
            $Mensaje = 'error__' . $e->getMessage();
            DB::rollBack();
        }
        return redirect()->route('categoria.index')->with('mensaje', $Mensaje);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $Categoria = Categoria::find($id);
            if ($Categoria->subcategoria->count() < 1) {
                $Categoria->delete();
                $Mensaje = 'success__Eliminado correctamente';
            } else if ($Categoria->estado == 1) {
                $Categoria->update(['estado' => 0]);
                $Mensaje = 'success__Se detectaron registrados asociados';
            } else {
                $Categoria->update(['estado' => 1]);
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
