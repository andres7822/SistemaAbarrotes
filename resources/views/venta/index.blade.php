@extends('template')

@section('title','Ventas')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Ventas</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item active">Ventas</li>
        </ol>

        <x-action-buttons-head routeName="venta"></x-action-buttons-head>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla Ventas
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Tipo Venta</th>
                        <th>Cliente</th>
                        <th>Usuario</th>
                        <th>Estatus Venta</th>
                        <th>Subtotal</th>
                        <th>Total</th>
                        <th>Pagado</th>
                        <th>Debe</th>
                        <th>Fecha Creación</th>
                        <th>Estado Cobro</th>
                        <th>Fecha Cobro</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($Ventas as $Venta)
                        <tr>
                            <!-- Folio -->
                            <td>{{ $Venta->folio }}</td>
                            <!-- Tipo Venta -->
                            <td>{{ $Venta->tipo_venta->nombre }}</td>
                            <!-- Cliente -->
                            <td>{{ $Venta->cliente->nombre ?? '' }}</td>
                            <!-- Usuario -->
                            <td>{{ $Venta->user->name }}</td>
                            <!-- Estatus Venta -->
                            <td>
                                <span
                                    class="{{ $Venta->estatus_venta->bgClass }}">{{ $Venta->estatus_venta->nombre }}</span>
                            </td>
                            <!-- Subtotal -->
                            <td>${{ number_format($Venta->subtotal , 2) }}</td>
                            <!-- Total -->
                            <td>${{ number_format($Venta->total , 2) }}</td>
                            <!-- Ultimo_pago -->
                            <td>${{ number_format($Venta->ultimo_pago , 2) }}</td>
                            <!-- Debe -->
                            <td>${{ number_format($Venta->debe , 2) }}</td>
                            <!-- Fecha Creación -->
                            <td>{{ $Venta->fecha }}</td>
                            <!-- Estado Cobro -->
                            <td class="text-center">
                                @if($Venta->estado_cobro == 1)
                                    <span class="fw-bolder rounded p-1 bg-success text-white">COBRADO</span>
                                @else
                                    <span class="fw-bolder rounded p-1 bg-danger text-white">PENDIENTE</span>
                                @endif
                            </td>
                            <!-- Fecha Cobro -->
                            <td>
                                @if($Venta->fecha_cobro)
                                    {{ $Venta->fecha_cobro }}
                                @else
                                    @if(count($Cajas) > 0)
                                        <div class="text-center">
                                            <button class="btn btn-sm btn-info"
                                                    onclick="ModalCobrar({{ json_encode($Venta) }})">
                                                <i class="fa fa-usd" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    @endif
                                @endif
                            </td>
                            @if($Venta->fecha_cobro == null)
                                <!--BOTONES ACCION-->
                                <td class="text-center">
                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">

                                        @if(auth()->user()->roles->first()->id == 1)
                                            <form
                                                action="{{ route('venta.edit', ['venta' => $Venta])}}"
                                                method="get">
                                                <button type="submit" class="btn btn-sm btn-warning" title="Editar">
                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                </button>&nbsp;
                                            </form>

                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#ConfirmModal-{{$Venta->id}}"
                                                    title="Eliminar">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </button>&nbsp;
                                        @else
                                            @can('editar-' . 'venta')
                                                <form
                                                    action="{{ route('venta.edit', ['venta' => $Venta])}}"
                                                    method="get">
                                                    <button type="submit" class="btn btn-sm btn-warning" title="Editar">
                                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                                    </button>&nbsp;
                                                </form>
                                            @endcan
                                            @can('eliminar-' . 'venta')
                                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#ConfirmModal-{{$Venta->id}}"
                                                        title="Eliminar">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>&nbsp;
                                            @endcan
                                        @endif


                                    </div>

                                    <!-- Modal Eliminar -->
                                    <div class="modal fade" id="ConfirmModal-{{$Venta->id}}" tabindex="-1"
                                         aria-labelledby="exampleModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                        ¿Estás seguro de eliminar el registro?
                                                    </h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>&nbsp;
                                                </div>
                                                <div class="modal-body">
                                                    Asegurese de eliminar el registro deseado ya que no se podrá
                                                    revertir.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cerrar
                                                    </button>&nbsp;
                                                    <form action="{{route('venta.destroy', ['venta' => $Venta->id])}}"
                                                          method="post">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">Eliminar</button>&nbsp;
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            @else
                                <td class="text-center">
                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                        <button class="btn btn-sm btn-info"
                                                onclick="imprimirTicket({{ json_encode($Venta) }})">
                                            <i class="fa fa-print" aria-hidden="true"></i>
                                        </button>
                                        {{-- <a href="{{ route('venta.imprimir_ticket') }}"
                                            class="btn btn-sm btn-danger" target="_blank">
                                             <i class="fa fa-print" aria-hidden="true"></i>
                                         </a>--}}
                                    </div>
                                </td>
                            @endif

                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <!-- Modal Cobrar -->
                <div class="modal fade" id="ModalCobrar" tabindex="-1" aria-labelledby="exampleModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>&nbsp;
                            </div>
                            <div class="modal-body">
                                <hr style="border-top: 3px solid black;">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-check-label">Total Venta: $<span
                                                id="spanTotal">0.00</span></label>
                                        <input type="hidden" name="inputTotalVenta" id="inputTotalVenta" value="0">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-check-label">Total Pagado: $<span
                                                id="spanPagado">0.00</span></label>
                                        <input type="hidden" name="inputTotalPagado" id="inputTotalPagado"
                                               value="0">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-check-label">Faltante: $<span
                                                id="spanFaltante">0.00</span></label>
                                        <input type="hidden" name="inputTotalFaltante" id="inputTotalFaltante"
                                               value="0">
                                    </div>
                                </div>
                                <hr style="border-top: 3px solid black;">
                                <div class="row">
                                    <x-form-element id="efectivo" colSize="4" classForm="formasPagos">
                                    </x-form-element>
                                    <x-form-element id="pago_con" colSize="4">
                                    </x-form-element>
                                    <x-form-element id="cambio" colSize="4" readonly="true">
                                    </x-form-element>
                                    <x-form-element id="tarjeta_debito" colSize="3" classForm="formasPagos">
                                    </x-form-element>
                                    <x-form-element id="tarjeta_credito" colSize="3" classForm="formasPagos">
                                    </x-form-element>
                                    <x-form-element id="transferencia" colSize="3" classForm="formasPagos">
                                    </x-form-element>
                                    <x-form-element id="deposito" colSize="3" classForm="formasPagos">
                                    </x-form-element>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success" onclick="CobrarVenta()">Aceptar</button>&nbsp;
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar
                                </button>&nbsp;
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection

@push('js')
    <script>
        let totalVenta = 0;
        let dataVenta = '';
        let faltante = 0;
        let cambio = 0;
        let venta_id = {{ $venta_id }}
        $(document).ready(function () {

            if (venta_id != 0) {
                imprimirTicket({id: venta_id})
            }

            $('#efectivo').keyup(function () {
                CalcularCambio();
            });

            $('#pago_con').keyup(function () {
                CalcularCambio();
            });

            $('.formasPagos').keyup(function () {
                CalcularDebe();
            });
        });

        const ModalCobrar = (data) => {
            console.log(data);
            $('#spanTotal').html(parseFloat(data.total).toFixed(2));
            $('#spanPagado').html(parseFloat(data.ultimo_pago).toFixed(2));
            $('#spanFaltante').html(parseFloat(data.debe).toFixed(2));

            $('.formasPagos').each(function () {
                $(this).val('');
            });

            $('#pago_con').val('');
            $('#cambio').val('');

            totalVenta = parseFloat(data.total);
            dataVenta = data;
            faltante = 0;
            $('#ModalCobrar').modal('show');
        }

        const CalcularCambio = () => {
            let efectivo = $('#efectivo').val() != '' ? $('#efectivo').val() * 1 : 0;
            let pagoCon = $('#pago_con').val() != '' ? $('#pago_con').val() * 1 : 0;
            cambio = pagoCon - efectivo;
            $('#cambio').val(cambio);
        }

        const CalcularDebe = () => {
            let totalPagado = parseFloat(dataVenta.ultimo_pago);
            $('.formasPagos').each(function () {
                totalPagado += $(this).val() != '' ? $(this).val() * 1 : 0;
            });
            faltante = totalVenta - totalPagado;

            $('#inputTotalPagado').val(totalPagado);
            $('#inputTotalFaltante').val(faltante);

            $('#spanPagado').html(totalPagado.toFixed(2));
            $('#spanFaltante').html(faltante.toFixed(2));
        }

        const CobrarVenta = () => {

            const efectivo = $('#efectivo').val() != '' ? $('#efectivo').val() : 0;
            const pago_con = $('#pago_con').val() != '' ? $('#pago_con').val() : 0;
            const tarjeta_debito = $('#tarjeta_debito').val() != '' ? $('#tarjeta_debito').val() : 0;
            const tarjeta_credito = $('#tarjeta_credito').val() != '' ? $('#tarjeta_credito').val() : 0;
            const transferencia = $('#transferencia').val() != '' ? $('#transferencia').val() : 0;
            const deposito = $('#deposito').val() != '' ? $('#deposito').val() : 0;

            const totalPagar = efectivo + tarjeta_debito + tarjeta_credito + transferencia + deposito;

            if (efectivo < 0 || pago_con < 0 || tarjeta_debito < 0 || tarjeta_credito < 0 || transferencia < 0 || deposito < 0) {
                SwalToast('No debe haber números menor a 0', 'warning', 3000, 'top');
                return;
            }
            if (cambio < 0) {
                SwalToast('Campo efectivo debe ser menor o igual al campo "pago con"', 'warning', 3000, 'top');
                return;
            }
            if (faltante < 0) {
                SwalToast('Total a pagar es mayor al valor de lo que debe', 'warning', 3000, 'top');
                return;
            }
            if (!(totalPagar > 0)) {
                SwalToast('Rellene los campos necesarios', 'warning', 3000, 'top');
                return;
            }

            SwalLoading('Cobrando...');
            $.ajax({
                data: {
                    '_token': "{{ csrf_token() }}",
                    efectivo,
                    pago_con,
                    tarjeta_debito,
                    tarjeta_credito,
                    transferencia,
                    deposito,
                    'idVenta': dataVenta.id
                },
                type: 'POST',
                url: "{{ route('venta.cobrar_venta') }}",
                dataType: 'json',
                success: function (response) {
                    if (response.status == 'success') {
                        SwalAlert(response.status, response.message)
                            .then(() => {
                                $('#datatablesSimple').load(location.href + ' #datatablesSimple > *');
                                $('#ModalCobrar').modal('hide');
                                imprimirTicket(dataVenta);
                            })
                    } else {
                        SwalAlert(response.status, response.message);
                    }
                }, error: function (error) {
                    SwalAlert('error', 'Ha ocurrido un error', error.responseJSON.message);
                }
            })
        }

        const imprimirTicket = (data) => {
            const url = "{{ route('venta.imprimir_ticket') }}?venta_id=" + data.id;
            window.open(url, '_blank');
        }


    </script>
@endpush
