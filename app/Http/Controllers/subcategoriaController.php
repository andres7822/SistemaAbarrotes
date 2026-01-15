<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubcategoriaRequest;
use App\Http\Requests\UpdateSubcategoriaRequest;
use App\Models\Categoria;
use App\Models\Subcategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class subcategoriaController extends actionPermissionController
{
    public function __construct()
    {
        parent::__construct('subcategoria');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Subcategorias = Subcategoria::with('categoria')
            ->get();
        return view('subcategoria.index', compact('Subcategorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $Categorias = Categoria::where('estado', 1)
            ->get();
        return view('subcategoria.create', compact('Categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubcategoriaRequest $request)
    {
        /*$requestArray = [
            'nombre' => 'required|max:64|unique:subcategorias,nombre'
        ];

        if ($request->categoria_id == -1) {
            $requestArray['nombre_categoria'] = 'required|max:64|unique:categorias,nombre';
        } else {
            $requestArray['categoria_id'] = 'required|exists:categorias,id';
        }

        $request->validate($requestArray);*/

        try {
            DB::beginTransaction();
            if ($request->categoria_id == -1) {
                $Categoria = Categoria::create([
                    'nombre' => $request->nombre_categoria
                ]);
                $request['categoria_id'] = $Categoria->id;
            }
            Subcategoria::create($request->all());
            $Mensaje = 'success__Agregado correctamente';
            DB::commit();
        } catch (\Exception $e) {
            $Mensaje = 'error__' . $e->getMessage();
            DB::rollBack();
        }
        if ($request->accion == 'continuar') {
            return redirect()->route('subcategoria.index')->with('mensaje', $Mensaje);
        }
        return redirect()->route('subcategoria.create')->with('mensaje', $Mensaje);
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
    public function edit(Subcategoria $Subcategoria)
    {
        $Categorias = Categoria::where('estado', 1)
            ->get();
        return view('subcategoria.edit', compact('Categorias', 'Subcategoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubcategoriaRequest $request, Subcategoria $Subcategoria)
    {
        try {
            DB::beginTransaction();
            if ($request->categoria_id == -1) {
                $Categoria = Categoria::create([
                    'nombre' => $request->nombre_categoria
                ]);
                $request['categoria_id'] = $Categoria->id;
            }
            $Subcategoria->update([
                'nombre' => $request->nombre,
                'categoria_id' => $request->categoria_id
            ]);
            $Mensaje = 'success__Actualizado correctamente';
            DB::commit();
        } catch (\Exception $e) {
            $Mensaje = 'error__' . $e->getMessage();
            DB::rollBack();
        }

        return redirect()->route('subcategoria.index')->with('mensaje', $Mensaje);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $Subcategoria = Subcategoria::find($id);
            if ($Subcategoria->producto->count() < 1) {
                $Subcategoria->delete();
                $Mensaje = 'success__Eliminado correctamente';
            } else if ($Subcategoria->estado == 1) {
                $Subcategoria->update(['estado' => 0]);
                $Mensaje = 'success__Se detectaron registrados asociados';
            } else {
                $Subcategoria->update(['estado' => 1]);
                $Mensaje = 'success__Restaurado correctamente';
            }
            DB::commit();
        } catch (\Exception $e) {
            $Mensaje = 'error__' . $e->getMessage();
            DB::rollBack();
        }
        return redirect()->route('subcategoria.index')->with('mensaje', $Mensaje);
    }

    public function subcategoriaByCat(Request $request)
    {
        try {
            $Subcategorias = Subcategoria::where('categoria_id', $request->idCategoria)
                ->where('estado', 1)
                ->orderBy('nombre')
                ->get();

            $Options = "<option value=''>SELECCIONA UNA OPCIÓN</option>";

            foreach ($Subcategorias as $Subcategoria) {
                $Options .= sprintf("
                <option value='%s'>%s</option>
                ", $Subcategoria->id, $Subcategoria->nombre);
            }

            return [
                'status' => 'success',
                'options' => $Options
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
