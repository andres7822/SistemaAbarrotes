<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCajaRequest;
use App\Http\Requests\UpdateCajaRequest;
use App\Models\Caja;
use App\Models\MovimientoCaja;
use App\Models\RegistroAccione;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class cajaController extends actionPermissionController
{
    public function __construct()
    {
        parent::__construct('caja');
    }

    public function index()
    {
        $Cajas = Caja::orderBy('id', 'desc')
            ->get();

        foreach ($Cajas as $index => $caja) {

            $Ingresos = MovimientoCaja::where('caja_id', $caja->id)
                ->where('tipo_movimiento_id', 1)
                ->get();

            $Egresos = MovimientoCaja::where('caja_id', $caja->id)
                ->where('tipo_movimiento_id', 2)
                ->get();

            $SumIngresos = 0;
            $SumIngresosEfectivo = 0;
            $SumEgresos = 0;
            $SumEgresosEfectivo = 0;

            foreach ($Ingresos as $ingreso) {
                $SumIngresos += $ingreso->monto * 1;
                if ($ingreso->tipo_ingreso_id == 1) {
                    $SumIngresosEfectivo += $ingreso->monto;
                }
            }
            foreach ($Egresos as $egreso) {
                $SumEgresos += $egreso;
                if ($egreso->tipo_ingreso_id == 1) {
                    $SumEgresosEfectivo += $egreso->monto * 1;
                }
            }

            $CorteSistema = $SumIngresos - $SumEgresos;
            $CorteEfectivoSistema = $SumIngresosEfectivo - $SumEgresosEfectivo;

            $Cajas[$index]['corte_efectivo_sistema'] = $CorteEfectivoSistema;
        }

        $CajaAbierta = Caja::whereNull('fecha_cierre')
            ->get();
        $CajaAbierta = count($CajaAbierta) > 0;

        return view('caja.index', compact('Cajas', 'CajaAbierta'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tienda_id = auth()->user()->tienda_id;
        $user_id = auth()->user()->id;

        $Caja = Caja::where([
            'tienda_id' => $tienda_id
        ])
            ->whereNull('fecha_cierre')
            ->get();

        if (sizeof($Caja) > 0) {
            return redirect()->route('caja.index')->with('mensaje', 'warning__Ya hay una caja abierta en esta tienda');
        }
        return view('caja.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCajaRequest $request)
    {
        try {
            DB::beginTransaction();
            $CajaArr = [
                'cantidad_inicial' => $request->cantidad_inicial,
                'user_id' => auth()->user()->id,
                'tienda_id' => auth()->user()->tienda_id
            ];
            $Caja = Caja::create($CajaArr);
            $Mensaje = 'success__Caja abierta correctamente';

            $this->register->registro('caja', $Caja->id, 4, $Caja->toArray());
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $Mensaje = 'error__' . $e->getMessage();
            return redirect()->route('caja.create')->with('mensaje', $Mensaje)->withInput();
        }

        if ($request->accion == 'continuar') {
            return redirect()->route('caja.index')->with('mensaje', $Mensaje);
        }
        return redirect()->route('caja.create')->with('mensaje', $Mensaje);
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
    public function edit(Caja $Caja)
    {
        Caja::find($Caja->id)
            ->update([
                'fecha_cierre' => Carbon::now()
            ]);
        $Ingresos = MovimientoCaja::where('caja_id', $Caja->id)
            ->where('tipo_movimiento_id', 1)
            ->get();

        $Egresos = MovimientoCaja::where('caja_id', $Caja->id)
            ->where('tipo_movimiento_id', 2)
            ->get();

        $Denominaciones = (array)json_decode($Caja->denominaciones);

        $SumIngresos = 0;
        $SumIngresosEfectivo = 0;
        $SumEgresos = 0;
        $SumEgresosEfectivo = 0;

        foreach ($Ingresos as $ingreso) {
            $SumIngresos += $ingreso->monto * 1;
            switch ($ingreso->tipo_ingreso_id) {
                case 1: //Efectivo
                    $SumIngresosEfectivo += $ingreso->monto;
                    break;
                case 2: //Tarjeta Débito
                    $Caja['tarjeta_debito'] += $ingreso->monto;
                    break;
                case 3: //Tarjeta Crédito
                    $Caja['tarjeta_credito'] += $ingreso->monto;
                    break;
                case 4: //Transferencia
                    $Caja['transferencia'] += $ingreso->monto;
                    break;
                case 5: //Depósito
                    $Caja['deposito'] += $ingreso->monto;
                    break;
            }
        }

        foreach ($Egresos as $egreso) {
            $SumEgresos += $egreso;
            if ($egreso->tipo_ingreso_id == 1) {
                $SumEgresosEfectivo += $egreso->monto * 1;
            }
        }

        $CorteSistema = $SumIngresos - $SumEgresos;
        $CorteEfectivoSistema = $SumIngresosEfectivo - $SumEgresosEfectivo;

        return view('caja.edit', compact('Ingresos', 'Egresos', 'SumIngresos', 'CorteSistema',
            'CorteEfectivoSistema', 'SumIngresosEfectivo', 'SumEgresosEfectivo', 'SumEgresos', 'Caja', 'Denominaciones'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCajaRequest $request, Caja $Caja)
    {
        //return $request;
        try {
            DB::beginTransaction();
            $denominaciones = [];
            if ($request['1000_pesos'] > 0) {
                $denominaciones['1000_pesos'] = $request['1000_pesos'];
            }
            if ($request['500_pesos'] > 0) {
                $denominaciones['500_pesos'] = $request['500_pesos'];
            }
            if ($request['200_pesos'] > 0) {
                $denominaciones['200_pesos'] = $request['200_pesos'];
            }
            if ($request['100_pesos'] > 0) {
                $denominaciones['100_pesos'] = $request['100_pesos'];
            }
            if ($request['50_pesos'] > 0) {
                $denominaciones['50_pesos'] = $request['50_pesos'];
            }
            if ($request['20_pesos'] > 0) {
                $denominaciones['20_pesos'] = $request['20_pesos'];
            }
            if ($request['10_pesos'] > 0) {
                $denominaciones['10_pesos'] = $request['10_pesos'];
            }
            if ($request['5_pesos'] > 0) {
                $denominaciones['5_pesos'] = $request['5_pesos'];
            }
            if ($request['2_pesos'] > 0) {
                $denominaciones['2_pesos'] = $request['2_pesos'];
            }
            if ($request['1_peso'] > 0) {
                $denominaciones['1_peso'] = $request['1_peso'];
            }
            if ($request['50_centavos'] > 0) {
                $denominaciones['50_centavos'] = $request['50_centavos'];
            }
            $Caja->update([
                'cambio_dejado' => $request->cambio_dejado,
                'observaciones' => $request->observaciones,
                'denominaciones' => json_encode($denominaciones),
                'cantidad_cierre' => $request->cantidad_cierre,
                'fecha_cierre' => Carbon::now()
            ]);
            DB::commit();
            $Mensaje = 'success__Caja cerrada correctamente';
            return redirect()->route('caja.index')->with('mensaje', $Mensaje);
        } catch (\Exception $e) {
            DB::rollBack();
            $Mensaje = 'error__' . $e->getMessage();
            return redirect()->route('caja.edit', ['caja' => $Caja])->with('mensaje', $Mensaje)->withInput();
        }
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
