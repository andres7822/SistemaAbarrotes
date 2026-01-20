<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVentaRequest;
use App\Http\Requests\UpdateVentaRequest;
use App\Models\Caja;
use App\Models\Cliente;
use App\Models\Inventario;
use App\Models\MovimientoCaja;
use App\Models\TipoVenta;
use App\Models\Venta;
use App\Models\VentaDetalle;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Error;

class ventaController extends actionPermissionController
{
    public function __construct()
    {
        parent::__construct('venta');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $venta_id = $request->venta_id ?? 0;
        if ($venta_id != 0) {
            $Venta = Venta::find($venta_id);
            if ($Venta->ultimo_pago == 0)
                $venta_id = 0;
        }
        $Ventas = Venta::orderBy('estado_cobro')
            ->orderBy('fecha', 'desc')
            ->get();
        $Cajas = Caja::whereNull('fecha_cierre')
            ->get();
        return view('venta.index', compact('Ventas', 'Cajas', 'venta_id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (old('venta_id') != null) {
            $Venta = Venta::find(old('venta_id'));
        } else {
            $user_id = auth()->user()->id;
            $tienda_id = auth()->user()->tienda_id;

            $Folio = Venta::select('folio')
                ->whereRaw('YEAR(fecha) = YEAR(NOW()) and tipo_venta_id = ?', 1)
                ->orderBy('id', 'desc')
                ->limit(1)
                ->get();

            $anio = Carbon::now()->year;
            if (count($Folio) > 0) {
                $aux = explode('_', $Folio[0]->folio);
                $Folio = $anio . '_' . ($aux[1] * 1 + 1);
            } else {
                $Folio = $anio . '_1';
            }

            $Venta = Venta::create([
                'user_id' => $user_id,
                'tipo_venta_id' => 1,
                'folio' => $Folio,
                'tienda_id' => $tienda_id
            ]);
        }

        $Inventarios = Inventario::with(['producto' => function ($query) {
            $query->orderBy('nombre');
        }])
            ->where('existencia', '>', 0)
            ->get();

        $Clientes = Cliente::where('estado', 1)
            ->orderBy('nombre')
            ->get();

        $TipoVentas = TipoVenta::all();

        $Caja = Caja::whereNull('fecha_cierre')
            ->get();

        return view('venta.create', compact('Clientes', 'Inventarios', 'TipoVentas', 'Venta', 'Caja'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVentaRequest $request)
    {
        try {
            DB::beginTransaction();
            /*$user_id = auth()->user()->id;

            $arrVenta = [
                'tipo_venta_id' => $request->tipo_venta_id,
                'cliente_id' => $request->cliente_id,
                'user_id' => $user_id,
                'debe' => $request->inputTotalFaltante,
                'venta_id' => $request->venta_id
            ];*/
            $arrVenta = [
                'cliente_id' => $request->cliente_id,
                'debe' => $request->inputTotalFaltante,
            ];

            $MovimientosCaja = [];

            if (isset($request->pagara)) {
                $arrVenta = array_merge($arrVenta, [
                    'efectivo' => $request->efectivo ?? 0,
                    'pago_con' => $request->pago_con ?? 0,
                    'tarjeta_debito' => $request->tarjeta_debito ?? 0,
                    'tarjeta_credito' => $request->tarjeta_credito ?? 0,
                    'transferencia' => $request->transferencia ?? 0,
                    'deposito' => $request->deposito ?? 0,
                    'ultimo_pago' => $request->inputTotalPagado
                ]);
                $Caja = Caja::whereNull('fecha_cierre')
                    ->first();

                $Concepto = 'Pago de la venta ' . $request->venta_id;
                if ($request->efectivo > 0) {
                    $MovimientosCaja[] = ['concepto' => $Concepto, 'tipo_ingreso_id' => 1, 'monto' => $request->efectivo,
                        'origen' => 'venta', 'tupla' => $request->venta_id, 'caja_id' => $Caja->id];
                }
                if ($request->tarjeta_debito > 0) {
                    $MovimientosCaja[] = ['concepto' => $Concepto, 'tipo_ingreso_id' => 2, 'monto' => $request->tarjeta_debito,
                        'origen' => 'venta', 'tupla' => $request->venta_id, 'caja_id' => $Caja->id];
                }
                if ($request->tarjeta_credito > 0) {
                    $MovimientosCaja[] = ['concepto' => $Concepto, 'tipo_ingreso_id' => 3, 'monto' => $request->tarjeta_credito,
                        'origen' => 'venta', 'tupla' => $request->venta_id, 'caja_id' => $Caja->id];
                }
                if ($request->transferencia > 0) {
                    $MovimientosCaja[] = ['concepto' => $Concepto, 'tipo_ingreso_id' => 4, 'monto' => $request->transferencia,
                        'origen' => 'venta', 'tupla' => $request->venta_id, 'caja_id' => $Caja->id];
                }
                if ($request->deposito > 0) {
                    $MovimientosCaja[] = ['concepto' => $Concepto, 'tipo_ingreso_id' => 5, 'monto' => $request->deposito,
                        'origen' => 'venta', 'tupla' => $request->venta_id, 'caja_id' => $Caja->id];
                }

                if ($request->inputTotalFaltante == 0) {
                    $arrVenta = array_merge($arrVenta, [
                        'estado_cobro' => 1,
                        'fecha_cobro' => Carbon::now()
                    ]);
                }
            }

            //$Venta = Venta::create($arrVenta);
            $Venta = Venta::find($request->venta_id);
            VentaDetalle::where('venta_id', $Venta->id)
                ->update([
                    'edit' => 0
                ]);

            $Subtotal = 0;
            $Total = 0;

            foreach ($MovimientosCaja as $item) {
                MovimientoCaja::create($item);
            }

            foreach ($request->inventarios as $index => $inventario) {
                $Inventario = Inventario::find($inventario);
                $ExistenciaActual = ($Inventario->existencia * 1) - ($request->cantidades[$index] * 1);
                if ($ExistenciaActual < 0) {
                    //   throw new Error('Actualización de existencias, el producto: ' . $Inventario->producto->nombre . ' no cuenta con las existencias suficientes');
                }
                $aux = ($request->cantidades[$index] * 1) * ($request->precios[$index] * 1);
                $Subtotal += $aux;
                $Total += $aux;

                /*$VentaDetalle = VentaDetalle::create([
                    'precio' => $request->precios[$index],
                    'cantidad' => $request->cantidades[$index],
                    'producto_id' => $Inventario->producto_id,
                    'inventario_id' => $inventario,
                    'venta_id' => $Venta->id
                ]);*/
                //$this->register->registro('venta_detalles', $VentaDetalle->id, 4, $VentaDetalle->toArray());
                $VentaDetalle = VentaDetalle::find($request->venta_detalle_id[$index]);
                $this->register->registro('venta_detalles', $request->venta_detalle_id[$index], 4, $VentaDetalle->toArray());

                $InvRegis = [
                    'ExistenciaAnterior' => $Inventario->existencia,
                    'CantidadVenta' => $request->cantidades[$index],
                    'ExistenciaActual' => $ExistenciaActual
                ];

                /*$Inventario->update([
                    'existencia' => $ExistenciaActual
                ]);*/
                //$this->register->registro('inventarios', $Inventario->id, 5, $InvRegis);
            }

            $arrVenta = array_merge($arrVenta, [
                'subtotal' => $Subtotal,
                'total' => $Total,
                'estatus_venta_id' => 3
            ]);

            $Venta->update($arrVenta);

            $this->register->registro('ventas', $Venta->id, 4, $Venta->toArray());
            $Mensaje = 'success__Creado correctamente';

            DB::commit();

        } catch (\Exception $e) {
            $Mensaje = 'error__' . $e->getMessage();
            DB::rollBack();
            return redirect()->route('venta.create')->with('mensaje', $Mensaje)->withInput();
        }

        if ($request->accion == 'continuar') {
            return redirect()->route('venta.index', ['venta_id' => $Venta->id])->with('mensaje', $Mensaje);
        }
        return redirect()->route('venta.create')->with('mensaje', $Mensaje);
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
    public function edit(Venta $Venta)
    {
        $Inventarios = Inventario::with(['producto' => function ($query) {
            $query->orderBy('nombre');
        }])
            //->where('existencia', '>', 0)
            ->get();

        $Clientes = Cliente::where('estado', 1)
            ->orderBy('nombre')
            ->get();

        $TipoVentas = TipoVenta::all();

        $Venta->update(['estatus_venta_id' => 2]);

        /*VentaDetalle::where('venta_id', $Venta->id)
            ->update([
                'edit' => 1
            ]);*/

        return view('venta.edit', compact('Clientes', 'Inventarios', 'TipoVentas', 'Venta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVentaRequest $request, Venta $Venta)
    {

        try {
            DB::beginTransaction();
            $Venta->update(['estatus_venta_id' => 3]);
            if ($request->accion == 'cancelar') {
                //Productos que fueron agregado al editar y que le dieron en cancelar, es decir, no se mantendrán en la venta
                $VentaDetalles = VentaDetalle::where('venta_id', $Venta->id)
                    ->where('edit', 2)
                    ->get();
                foreach ($VentaDetalles as $index => $detalle) {
                    $Inventario = Inventario::find($detalle->inventario_id);
                    $Inventario->update([
                        'existencia' => $Inventario->existencia + $detalle->cantidad
                    ]);
                    $VentaDetalles[$index]->delete();
                }

                //Productos que ya estaban agregados antes de  editar y que le dieron en cancelar, es decir, se mantendrán en la venta
                $VentaDetalles = VentaDetalle::where('venta_id', $Venta->id)
                    ->where('edit', 1)
                    ->get();

                foreach ($VentaDetalles as $index => $detalle) {
                    $Inventario = Inventario::find($detalle->inventario_id);
                    $Inventario->update([
                        'existencia' => $Inventario->existencia - $detalle->cantidad
                    ]);
                    $VentaDetalles[$index]->update(['edit' => 0]);
                }

                DB::commit();
                return redirect()->route('venta.index');
            } else {

                VentaDetalle::where('venta_id', $Venta->id)
                    ->where('edit', 2)
                    ->update(['edit' => 0]);

                VentaDetalle::where('venta_id', $Venta->id)
                    ->where('edit', 1)
                    ->delete();

                $Venta->update([
                    'subtotal' => $request->inputTotalVenta,
                    'total' => $request->inputTotalVenta,
                    'debe' => $request->inputTotalFaltante,
                    'cliente_id' => $request->cliente_id
                ]);

                $Mensaje = 'success__Actualizado correctamente';
                DB::commit();
                return redirect()->route('venta.index')->with('mensaje', $Mensaje);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $Mensaje = 'error__' . $e->getMessage();
            return redirect()->route('venta.edit', ['venta' => $Venta])->with('mensaje', $Mensaje)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $Venta = Venta::find($id);

            foreach ($Venta->venta_detalle as $index => $item) {
                $Inventario = Inventario::find($item->inventario_id);
                $Inventario->update([
                    'existencia' => $Inventario->existencia + $item->cantidad
                ]);
            }

            $Venta->delete();
            $Mensaje = 'success__Eliminado correctamente';
            DB::commit();
        } catch (\Exception $e) {
            $Mensaje = 'error__' . $e->getMessage();
            DB::rollBack();
        }
        return redirect()->route('venta.index')->with('mensaje', $Mensaje);
    }

    public function actualizarCliente(Request $request)
    {
        try {
            Venta::find($request->venta_id)
                ->update([
                    'cliente_id' => $request->cliente_id
                ]);
            return [
                'status' => 'success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function cobrarVenta(Request $request)
    {
//        return $request;
        try {
            DB::beginTransaction();
            $Venta = Venta::find($request->idVenta);

            $PagoActual = $request->efectivo + $request->tarjeta_debito + $request->tarjeta_credito + $request->transferencia + $request->deposito;
            $Debe = ($Venta->debe * 1) - $PagoActual;
            $UltimoPago = ($Venta->ultimo_pago * 1) + $PagoActual;
            $EstadoCobro = $Debe == 0 ? 1 : 0;
            $FechaCobro = $EstadoCobro == 1 ? Carbon::now() : null;

            $Caja = Caja::whereNull('fecha_cierre')
                ->first();

            $MovimientosCaja = [];
            $Concepto = 'Pago de la venta ' . $request->idVenta;
            if ($request->efectivo > 0) {
                $MovimientosCaja[] = ['concepto' => $Concepto, 'tipo_ingreso_id' => 1, 'monto' => $request->efectivo,
                    'pago_con' => $request->pago_con, 'origen' => 'venta', 'tupla' => $request->idVenta,
                    'caja_id' => $Caja->id];
            }
            if ($request->tarjeta_debito > 0) {
                $MovimientosCaja[] = ['concepto' => $Concepto, 'tipo_ingreso_id' => 2, 'monto' => $request->tarjeta_debito,
                    'origen' => 'venta', 'tupla' => $request->idVenta, 'caja_id' => $Caja->id];
            }
            if ($request->tarjeta_credito > 0) {
                $MovimientosCaja[] = ['concepto' => $Concepto, 'tipo_ingreso_id' => 3, 'monto' => $request->tarjeta_credito,
                    'origen' => 'venta', 'tupla' => $request->idVenta, 'caja_id' => $Caja->id];
            }
            if ($request->transferencia > 0) {
                $MovimientosCaja[] = ['concepto' => $Concepto, 'tipo_ingreso_id' => 4, 'monto' => $request->transferencia,
                    'origen' => 'venta', 'tupla' => $request->idVenta, 'caja_id' => $Caja->id];
            }
            if ($request->deposito > 0) {
                $MovimientosCaja[] = ['concepto' => $Concepto, 'tipo_ingreso_id' => 5, 'monto' => $request->deposito,
                    'origen' => 'venta', 'tupla' => $request->idVenta, 'caja_id' => $Caja->id];
            }

            $Venta->update([
                'efectivo' => $request->efectivo,
                'pago_con' => $request->pago_con,
                'tarjeta_debito' => $request->tarjeta_debito,
                'tarjeta_credito' => $request->tarjeta_credito,
                'transferencia' => $request->transferencia,
                'deposito' => $request->deposito,
                'debe' => $Debe,
                'ultimo_pago' => $UltimoPago,
                'estado_cobro' => $EstadoCobro,
                'fecha_cobro' => $FechaCobro
            ]);

            foreach ($MovimientosCaja as $item) {
                MovimientoCaja::create($item);
            }

            if ($EstadoCobro == 1) {
                $Mensaje = 'Venta pagada completamente';
            } else {
                $Mensaje = 'Abono a venta correctamente';
            }

            DB::commit();
            return [
                'status' => 'success',
                'message' => $Mensaje
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function imprimirTicket(Request $request)
    {
        $venta_id = $request->venta_id;

        $Venta = Venta::with('venta_detalle')
            ->find($venta_id);

        /*$pdf = Pdf::loadView('venta.imprimirTicket', compact('Venta'));

        return $pdf->stream($Venta->folio . '.pdf');*/
        return view('venta.imprimirTicket', compact('Venta'));
    }
}
