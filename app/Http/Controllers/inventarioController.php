<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventarioRequest;
use App\Http\Requests\UpdateInventarioRequest;
use App\Models\Bodega;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\VentaDetalle;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class inventarioController extends actionPermissionController
{
    public function __construct()
    {
        parent::__construct('inventario');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Inventarios = Inventario::all();
        return view('inventario.index', compact('Inventarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $Productos = Producto::where('estado', 1)
            ->orderBy('nombre')
            ->get();

        $Bodegas = Bodega::where('estado', 1)
            ->orderBy('nombre')
            ->get();

        return view('inventario.create', compact('Productos', 'Bodegas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInventarioRequest $request)
    {
        try {
            DB::beginTransaction();
            $Inventario = Inventario::create($request->all());
            $Mensaje = 'success__Agregado correctamente';
            $this->register->registro('inventarios', $Inventario->id, 4, $Inventario->toArray());
            DB::commit();
        } catch (Exception $e) {
            $Mensaje = 'error__' . $e->getMessage();
            DB::rollBack();
            return redirect()->route('inventario.create')->with('mensaje', $Mensaje);
        }

        if ($request->accion == 'continuar') {
            return redirect()->route('inventario.index')->with('mensaje', $Mensaje);
        }
        return redirect()->route('inventario.create')->with('mensaje', $Mensaje);
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
    public function edit(Inventario $Inventario)
    {
        $Productos = Producto::where('estado', 1)
            ->orderBy('nombre')
            ->get();

        $Bodegas = Bodega::where('estado', 1)
            ->orderBy('nombre')
            ->get();

        return view('inventario.edit', compact('Productos', 'Bodegas', 'Inventario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInventarioRequest $request, Inventario $Inventario)
    {
        try {
            DB::beginTransaction();
            $Inventario->update($request->except('_token', '_method'));
            $Mensaje = 'success__Actualizado correctamente';
            $this->register->registro('inventarios', $Inventario->id, 5, $Inventario->toArray());
            DB::commit();
        } catch (Exception $e) {
            $Mensaje = 'error__' . $e->getMessage();
            DB::rollBack();
        }
        return redirect()->route('inventario.index')->with('mensaje', $Mensaje);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            Inventario::find($id)
                ->delete();
            $Mensaje = 'success__Eliminado correctamente';
            DB::commit();
        } catch (Exception $e) {
            $Mensaje = 'error__' . $e->getMessage();
            DB::rollBack();
        }
        return redirect()->route('inventario.index')->with('mensaje', $Mensaje);
    }

    public function listadoSelect(Request $request)
    {
        try {
            $Inventarios = Inventario::with(['producto' => function ($query) {
                $query->orderBy('nombre');
            }])
                ->where('existencia', '>', 0)
                ->get();

            $options = "<option value=''>SELECCIONE UN PRODUCTO...</option>";

            foreach ($Inventarios as $Inventario) {
                $options .= sprintf("
                                <option value='%s'
                                        id='option_%s'
                                        data-nombre='%s'
                                        data-existencia='%s'
                                        data-idproducto='%s'
                                        data-precio='%s'
                                        data-bodega='%s'
                                >
                                    %s -- %s -- %s
                                </option>
                ", $Inventario->id, $Inventario->id, $Inventario->producto->nombre, $Inventario->existencia,
                    $Inventario->producto->id, $Inventario->producto->precio_venta, $Inventario->bodega->nombre,
                    $Inventario->producto->nombre, $Inventario->bodega->nombre, $Inventario->existencia);
            }
            return [
                'status' => 'success',
                'options' => $options
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'options' => $e->getMessage()
            ];
        }
    }

    public function actualizarInventario(Request $request)
    {
        //return array_merge($request->toArray(), ['status' => 'error']);
        try {
            DB::beginTransaction();
            $Inventario = Inventario::find($request->idInventario);
            $venta_detalle_id = -1;
            if ($request->index == '-1') { //Agregar producto a venta
                $Existencia = $Inventario->existencia - $request->cantidad * 1;
                $Producto = Producto::find($Inventario->producto_id);

                $VentaDetalle = VentaDetalle::create([
                    'precio' => $Producto->precio_venta,
                    'cantidad' => $request->cantidad,
                    'producto_id' => $Producto->id,
                    'inventario_id' => $Inventario->id,
                    'venta_id' => $request->venta_id,
                    'edit' => 2
                ]);
                $venta_detalle_id = $VentaDetalle->id;
            } else { //Quitar producto de la lista de la venta
                $Existencia = ($Inventario->existencia * 1) + ($request->cantidad * 1);
                //return ['existencia' => $Existencia];
                if (isset($request->isEdit) && $request->isEdit != '') {
                    $VentaDetalle = VentaDetalle::find($request->venta_detalle_id);
                    if ($VentaDetalle->edit == 2) {
                        $VentaDetalle->delete();
                    } else {
                        $VentaDetalle->update(['edit' => 1]);
                    }
                } else {
                    VentaDetalle::find($request->venta_detalle_id)->delete();
                }
            }
            $Inventario->update([
                'existencia' => $Existencia
            ]);
            DB::commit();
            return [
                'status' => 'success',
                'venta_detalle_id' => $venta_detalle_id
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function reporte()
    {
        try {
            $Inventarios = Inventario::all();

            $pdf = Pdf::loadView('inventario.reporte', compact('Inventarios'));

            return $pdf->stream('Inventario.pdf');
        } catch (\Exception $e) {
            print $e;
            exit();
        }
    }
}
