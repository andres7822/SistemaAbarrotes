<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Subcategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class subcategoriaController extends Controller
{
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
    public function store(Request $request)
    {
        $requestArray = [
            'nombre' => 'required|max:64|unique:subcategorias,nombre'
        ];

        if ($request->categoria_id == -1) {
            $requestArray['nombre_categoria'] = 'required|max:64|unique:categorias,nombre';
        } else {
            $requestArray['categoria_id'] = 'required|exists:categorias,id';
        }

        $request->validate($requestArray);

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
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
