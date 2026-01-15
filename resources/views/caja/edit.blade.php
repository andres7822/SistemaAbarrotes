@extends('template')

@section('title','Editar Caja')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Editar Caja: {{$Caja->nombre}}</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{route('caja.index')}}">Cajas</a></li>
            <li class="breadcrumb-item active">Editar Caja</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{route('caja.update', ['caja' => $Caja])}}" method="post" autocomplete="off">
                @method('PUT')
                @csrf
                {{--PARA ENVIAR FORMULARIOS--}}
                <div class="row g-3">
                    <!-- SumIngresos -->
                    <x-form-element id="sum_ingresos" label="Ingresos" value="{{ $SumIngresos }}" readonly="true"
                                    colSize="4"></x-form-element>

                    <!-- SumEgresos -->
                    <x-form-element id="sum_egresos" label="Egresos" value="{{ $SumEgresos }}" readonly="true"
                                    colSize="4"></x-form-element>

                    <!-- CorteSistema -->
                    <x-form-element id="corte_sistema" label="Total" value="{{ $CorteSistema }}" readonly="true"
                                    colSize="4"></x-form-element>

                    <!-- Tarjeta Débito -->
                    <x-form-element id="tarjeta_debito" colSize="3" value="{{ $Caja->tarjeta_debito ?? '0' }}" readonly="true"></x-form-element>

                    <!-- Tarjeta Crédito -->
                    <x-form-element id="tarjeta_credito" colSize="3" value="{{ $Caja->tarjeta_credito ?? '0' }}" readonly="true"></x-form-element>

                    <!-- Transferencia -->
                    <x-form-element id="transferencia" colSize="3" value="{{ $Caja->transferencia ?? '0' }}" readonly="true"></x-form-element>

                    <!-- Depòsito -->
                    <x-form-element id="Depósito" colSize="3" value="{{ $Caja->deposito ?? '0' }}" readonly="true"></x-form-element>

                    <!-- SumIngresosEfectivo -->
                    <x-form-element id="sum_egresos_efectivo" label="Ingresos Efectivo"
                                    value="{{ $SumIngresosEfectivo }}"
                                    readonly="true" colSize="4"></x-form-element>

                    <!-- SumEgresosEfectivo -->
                    <x-form-element id="sum_egresos_efectivo" label="Egresos Efectivo" value="{{ $SumEgresosEfectivo }}"
                                    readonly="true" colSize="4"></x-form-element>

                    <!-- CorteEfectivoSistema -->
                    <x-form-element id="corte_efectivo_sistema" label="Total Efectivo"
                                    value="{{ $CorteEfectivoSistema }}"
                                    readonly="true" colSize="4"></x-form-element>

                    <hr style="border-top: 3px solid black;">

                    <x-form-element id="1000_pesos" colSize="4"
                                    placeholder="Billetes de 1000" classForm="conteo"
                                    dataOptions="data-valor=1000"
                                    value="{{ $Denominaciones['1000_pesos'] ?? '' }}"></x-form-element>

                    <x-form-element id="500_pesos" colSize="4"
                                    placeholder="Billetes de 500" classForm="conteo"
                                    dataOptions="data-valor=500"
                                    value="{{ $Denominaciones['500_pesos'] ?? '' }}"></x-form-element>

                    <x-form-element id="200_pesos" colSize="4"
                                    placeholder="Billetes de 200" classForm="conteo"
                                    dataOptions="data-valor=200"
                                    value="{{ $Denominaciones['200_pesos'] ?? '' }}"></x-form-element>

                    <x-form-element id="100_pesos" colSize="4"
                                    placeholder="Billetes de 100" classForm="conteo"
                                    dataOptions="data-valor=100"
                                    value="{{ $Denominaciones['100_pesos'] ?? '' }}"></x-form-element>

                    <x-form-element id="50_pesos" colSize="4"
                                    placeholder="Billetes de 50" classForm="conteo"
                                    dataOptions="data-valor=50"
                                    value="{{ $Denominaciones['50_pesos'] ?? '' }}"></x-form-element>

                    <x-form-element id="20_pesos" colSize="4"
                                    placeholder="Billetes de 20" classForm="conteo"
                                    dataOptions="data-valor=20"
                                    value="{{ $Denominaciones['20_pesos'] ?? '' }}"></x-form-element>

                    <x-form-element id="10_pesos" colSize="4"
                                    placeholder="Monedas de 10" classForm="conteo"
                                    dataOptions="data-valor=10"
                                    value="{{ $Denominaciones['10_pesos'] ?? '' }}"></x-form-element>

                    <x-form-element id="5_pesos" colSize="4" placeholder="Monedas de 5"
                                    classForm="conteo" dataOptions="data-valor=5"
                                    value="{{ $Denominaciones['5_pesos'] ?? '' }}"></x-form-element>

                    <x-form-element id="2_pesos" colSize="4" placeholder="Monedas de 2"
                                    classForm="conteo" dataOptions="data-valor=2"
                                    value="{{ $Denominaciones['2_pesos'] ?? '' }}"></x-form-element>

                    <x-form-element id="1_peso" colSize="4" placeholder="Monedas de 1"
                                    classForm="conteo" dataOptions="data-valor=1"
                                    value="{{ $Denominaciones['1_peso'] ?? '' }}"></x-form-element>

                    <x-form-element id="50_centavos" colSize="4"
                                    placeholder="Monedas de 50 centavos" classForm="conteo"
                                    dataOptions="data-valor=0.50"
                                    value="{{ $Denominaciones['50_centavos'] ?? '' }}"></x-form-element>

                    <hr style="border-top: 3px solid black;">

                    <!-- Cantidad Inicial -->
                    <x-form-element id="cantidad_inicial" value="{{ $Caja->cantidad_inicial }}"
                                    readonly="true" colSize="3"></x-form-element>

                    <!-- Total Contado -->
                    <x-form-element id="total_contado" required="true"
                                    value="{{ $Caja->cambio_dejado }}" colSize="3" readonly="true"></x-form-element>

                    <input type="hidden" id="cantidad_cierre" name="cantidad_cierre">

                    <!-- Cambio Dejado -->
                    <x-form-element id="cambio_dejado" value="{{ $Caja->cambio_dejado }}"
                                    required="true" colSize="3"></x-form-element>

                    <!-- Total Usuario -->
                    <x-form-element id="total_usuario" required="true" colSize="3" readonly="true"></x-form-element>

                    <hr style="border-top: 3px solid black;">

                    <!-- Total Usuario -->
                    <x-form-element id="total_usuario2" label="Total Usuario" required="true" colSize="4" readonly="true"></x-form-element>

                    <!-- CorteEfectivoSistema -->
                    <x-form-element id="corte_efectivo_sistema" label="Total Efectivo (sistema)"
                                    value="{{ $CorteEfectivoSistema }}"
                                    readonly="true" colSize="4"></x-form-element>

                    <x-form-element id="diferencia" colSize="4" readonly="true"></x-form-element>

                    <hr style="border-top: 3px solid black;">

                    <!-- Observaciones -->
                    <x-form-element id="observaciones" type="textarea"
                                    value="{{ $Caja->observaciones }}"></x-form-element>

                    <!-- Botones -->
                    <x-form-buttons routeName="caja" isEdit="true"></x-form-buttons>

                </div>
            </form>
        </div>

    </div>

@endsection

@push('js')
    <script>
        let totalContado = 0;
        let totalUsuario = 0;
        let corteEfectivoSistema = {{ $CorteEfectivoSistema }};

        $(document).ready(function () {
            CalcularTotalContado();
            CalcularTotalUsuario();
            CalcularDiferencia();

            $('.conteo').keyup(function () {
                CalcularTotalContado();
                CalcularTotalUsuario();
                CalcularDiferencia();
            });

            $('#cambio_dejado').keyup(function () {
                CalcularTotalContado();
                CalcularTotalUsuario();
                CalcularDiferencia();
            });
        });

        const CalcularTotalContado = () => {
            totalContado = 0;
            $('.conteo').each(function () {
                const valInput = $(this).val();
                if (valInput > 0) {
                    const valor = $(this).data('valor') * 1;
                    totalContado += (valInput * valor);
                }
                $('#total_contado').val(totalContado);
                $('#cantidad_cierre').val(totalContado);
            });
        }

        const CalcularTotalUsuario = () => {
            const cantidadInicial = $('#cantidad_inicial').val() * 1;
            const cambioDejado = $('#cambio_dejado').val() * 1;
            totalUsuario = cantidadInicial + (totalContado * 1) + cambioDejado;
            $('#total_usuario').val(totalUsuario);
            $('#total_usuario2').val(totalUsuario);
        }

        const CalcularDiferencia = () => {
            const diferencia = totalUsuario - corteEfectivoSistema;
            $('#diferencia').val(diferencia);
        }

    </script>
@endpush
