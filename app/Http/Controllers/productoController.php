<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Presentacione;
use App\Models\Producto;
use App\Models\Subcategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class productoController extends actionPermissionController
{
    public function __construct()
    {
        parent::__construct('producto');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Productos = Producto::all();
        return view('producto.index', compact('Productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $Marcas = Marca::where('estado', 1)
            ->get();
        $Presentaciones = Presentacione::where('estado', 1)
            ->get();
        $Categorias = Categoria::where('estado', 1)
            ->get();

        return view('producto.create', compact('Marcas', 'Presentaciones', 'Categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductoRequest $request)
    {
        //return $request;
        try {
            DB::beginTransaction();
            $NameImage = null;
            if ($request->hasFile('imagen')) {
                $NameImage = Producto::handleUploadImage($request->file('imagen'));
            }
            Producto::create([
                'nombre' => $request->nombre,
                'codigo_barras' => $request->codigo_barras,
                'descripcion' => $request->descripcion,
                'precio_venta' => $request->precio_venta,
                'costo' => $request->costo,
                'imagen' => $NameImage,
                'marca_id' => $request->marca_id,
                'presentacione_id' => $request->presentacione_id,
                'subcategoria_id' => $request->subcategoria_id
            ]);
            $Mensaje = 'success__Agregado correctamente';
            DB::commit();
        } catch (\Exception $e) {
            $Mensaje = 'error__' . $e->getMessage();
            DB::rollBack();
        }
        if ($request->accion == 'continuar') {
            return redirect()->route('producto.index')->with('mensaje', $Mensaje);
        }
        return redirect()->route('producto.create')->with('mensaje', $Mensaje);
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
    public function edit(Producto $Producto)
    {
        $Marcas = Marca::where('estado', 1)
            ->get();
        $Presentaciones = Presentacione::where('estado', 1)
            ->get();
        $Categorias = Categoria::where('estado', 1)
            ->get();

        $Subcategorias = Subcategoria::where('categoria_id', $Producto->subcategoria->categoria_id)
            ->where('estado', 1)
            ->get();

        return view('producto.edit', compact('Marcas', 'Presentaciones', 'Categorias', 'Subcategorias', 'Producto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductoRequest $request, Producto $Producto)
    {
        try {
            DB::beginTransaction();
            $NameImage = $Producto->imagen;
            if ($request->hasFile('imagen')) {
                $NameImage = Producto::handleUploadImage($request->file('imagen'));
                if (Storage::disk('public')->exists('/productos/' . $Producto->imagen)) {
                    Storage::disk('public')->delete('/productos/' . $Producto->imagen);
                }
            }
            $Producto->update([
                'nombre' => $request->nombre,
                'codigo_barras' => $request->codigo_barras,
                'descripcion' => $request->descripcion,
                'precio_venta' => $request->precio_venta,
                'costo' => $request->costo,
                'imagen' => $NameImage,
                'marca_id' => $request->marca_id,
                'presentacione_id' => $request->presentacione_id,
                'subcategoria_id' => $request->subcategoria_id
            ]);
            $Mensaje = 'success__Agregado correctamente';
            DB::commit();
        } catch (\Exception $e) {
            $Mensaje = 'error__' . $e->getMessage();
            DB::rollBack();
        }
        return redirect()->route('producto.index')->with('mensaje', $Mensaje);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
