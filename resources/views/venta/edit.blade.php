@extends('template')

@section('title','Editar Venta')

@push('css')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Editar Venta: {{$Venta->nombre}}</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{route('venta.index')}}">Ventas</a></li>
            <li class="breadcrumb-item active">Editar Venta</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{route('venta.update', ['venta' => $Venta])}}" method="post" autocomplete="off">
                @method('PUT')
                @csrf
                {{--PARA ENVIAR FORMULARIOS--}}
                <div class="row g-3">

                    <!-- Tipo Venta -->
                    <x-form-element id="tipo_venta_id" type="select" label="Tipo De Venta" required="true"
                                    value="{{ $Venta->tipo_venta_id }}" focused="true" :params="$TipoVentas"
                                    colSize="6">
                    </x-form-element>

                    <!-- Cliente -->
                    <x-form-element id="cliente_id" type="select" required="true" :params="$Clientes"
                                    value="{{ $Venta->cliente_id }}" colSize="6"></x-form-element>

                    <!-- Producto -->
                    <div class="col-md-4">
                        <label for="inventario_id" class="form-label">Producto: </label>
                        <select data-live-search="true" class="form-control selectpicker show-tick" name="inventario_id"
                                id="inventario_id" data-size="5">
                            <option value="">SELECCIONE UNA OPCIÓN...</option>
                            {{--@foreach($Inventarios as $Inventario)
                                <option value="{{ $Inventario->id }}"
                                        id="option_{{ $Inventario->id }}"
                                        data-nombre="{{ $Inventario->producto->nombre }}"
                                        data-existencia="{{ $Inventario->existencia }}"
                                        data-idproducto="{{ $Inventario->producto->id }}"
                                        data-precio="{{ $Inventario->producto->precio_venta }}"
                                >
                                    {{ $Inventario->producto->nombre }} -- {{ $Inventario->existencia }}
                                </option>
                            @endforeach--}}
                        </select>
                        @error('inventario_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Existencia -->
                    <div class="col-md-4">
                        <label for="existencia" class="form-label">Existencias:</label>
                        <input type="text" class="form-control" id="existencia" disabled>
                    </div>

                    <!-- Precio Venta -->
                    <div class="col-md-4">
                        <label for="precio_venta" class="form-label">Precio:</label>
                        <input type="text" class="form-control" id="precio_venta" disabled>
                    </div>

                    <!-- Cantidad -->
                    <div class="col-md-4">
                        <label for="cantidad" class="form-label">Cantidad:</label>
                        <input type="text" id="cantidad" class="form-control">
                    </div>

                    <!-- Total -->
                    <div class="col-md-4">
                        <label for="total" class="form-label">Total:</label>
                        <input type="text" id="total" class="form-control" disabled>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <button onclick="AgregarVentaDetalle()" type="button" class="form-control btn btn-success">
                            Agregar
                        </button>
                    </div>

                    <hr style="border-top: 3px solid black;">

                    <div class="col-md-4">
                        <label class="form-check-label">Total Venta: $<span id="spanTotal">0.00</span></label>
                        <input type="hidden" name="inputTotalVenta" id="inputTotalVenta" value="0">
                    </div>

                    <div class="col-md-4">
                        <label class="form-check-label">Total Pagado: $<span
                                    id="spanPagado">{{ number_format(old('inputTotalPagado', $Venta->ultimo_pago), 2) }}</span></label>
                        <input type="hidden" name="inputTotalPagado" id="inputTotalPagado"
                               value="{{ old('inputTotalPagado', $Venta->ultimo_pago) }}<">
                    </div>

                    <div class="col-md-4">
                        <label class="form-check-label">Faltante: $<span id="spanFaltante">0.00</span></label>
                        <input type="hidden" name="inputTotalFaltante" id="inputTotalFaltante" value="0">
                    </div>

                    {{--<div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="pagara"
                               name="pagara" {{ old('pagara') ? 'checked' : '' }}>
                        <label class="form-check-label" for="pagara">
                            Pagará
                        </label>
                    </div>--}}

                    <div id="formasDePago" class="row collapse {{ old('pagara') ? 'show' : '' }}">
                        <x-form-element id="efectivo" colSize="4" classForm="formasPagos"></x-form-element>
                        <x-form-element id="pago_con" colSize="4"></x-form-element>
                        <x-form-element id="cambio" colSize="4" readonly="true"></x-form-element>
                        <x-form-element id="tarjeta_debito" colSize="3" classForm="formasPagos"></x-form-element>
                        <x-form-element id="tarjeta_credito" colSize="3" classForm="formasPagos"></x-form-element>
                        <x-form-element id="transferencia" colSize="3" classForm="formasPagos"></x-form-element>
                        <x-form-element id="deposito" colSize="3" classForm="formasPagos"></x-form-element>
                    </div>

                    <hr style="border-top: 3px solid black;">

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="tBodyVentaDetalle">
                            @if(old('inventarios'))
                                @foreach(old('inventarios') as $i => $inv)
                                    <tr id="trProducto{{ $i }}">
                                        <!-- Nombre -->
                                        <td>
                                            {{ old('nombres')[$i] }}
                                            <input type="hidden" name="inventarios[]" id="inventarioInput_{{ $i }}"
                                                   class="inventarios"
                                                   value="{{ $inv }}">
                                            <input type="hidden" name="nombres[]" value="{{ old('nombres')[$i] }}">
                                            <input type="hidden" name="venta_detalle_id[]"
                                                   id="venta_detalle_id_{{ $i }}"
                                                   value="{{ old('venta_detalles')[$i] }}">
                                        </td>
                                        <!-- Precio -->
                                        <td>
                                            ${{ number_format(old('precios')[$i], 2) }}
                                            <input type="hidden" name="precios[]" value="{{ old('precios')[$i] }}">
                                        </td>
                                        <!-- Cantidad -->
                                        <td>
                                            {{ old('cantidades')[$i] }}
                                            <input type="hidden" name="cantidades[]"
                                                   id="cantidadInput_{{ $i }}"
                                                   value="{{ old('cantidades')[$i] }}">
                                        </td>
                                        <!-- totales -->
                                        <td>
                                            ${{ number_format(old('totales')[$i], 2) }}
                                            <input type="hidden" name="totales[]" class="totales"
                                                   value="{{ old('totales')[$i] }}">
                                        </td>
                                        <!-- Botones -->
                                        <td class="text-center">
                                            <button type="button" onclick="EliminarVentaDetalle({{ $i }})"
                                                    class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @foreach($Venta->venta_detalle as $i => $detalle)
                                    <tr id="trProducto{{ $i }}">
                                        <!-- Nombre -->
                                        <td>
                                            {{ $detalle->producto->nombre }}
                                            <input type="hidden" name="inventarios[]" id="inventarioInput_{{ $i }}"
                                                   class="inventarios"
                                                   value="{{ $detalle->inventario_id }}">
                                            <input type="hidden" name="nombres[]"
                                                   value="{{ $detalle->producto->nombre }}">
                                            <input type="hidden" id="venta_detalle_id_{{ $i }}"
                                                   name="venta_detalle_id[]"
                                                   value="{{ $detalle->id }}">
                                        </td>
                                        <!-- Precio -->
                                        <td>
                                            ${{ number_format($detalle->precio, 2) }}
                                            <input type="hidden" name="precios[]" value="{{ $detalle->precio }}">
                                        </td>
                                        <!-- Cantidad -->
                                        <td>
                                            {{ $detalle->cantidad }}
                                            <input type="hidden" name="cantidades[]"
                                                   id="cantidadInput_{{ $i }}"
                                                   value="{{ $detalle->cantidad }}">
                                        </td>
                                        <!-- totales -->
                                        <td>
                                            ${{ number_format(($detalle->cantidad * $detalle->precio), 2) }}
                                            <input type="hidden" name="totales[]" class="totales"
                                                   value="{{ ($detalle->cantidad * $detalle->precio) }}">
                                        </td>
                                        <!-- Botones -->
                                        <td class="text-center">
                                            <button type="button" onclick="EliminarVentaDetalle({{ $i }})"
                                                    class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Botones -->
                    {{--<x-form-buttons routeName="venta" isEdit="true"></x-form-buttons>--}}
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-success" name="accion" value="actualizar">Actualizar
                        </button>
                        <button type="submit" class="btn btn-danger" name="accion" value="cancelar">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>

    </div>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script>
        const venta_id = {{ old('venta_id', $Venta->id) }};
        let idInventario = '';
        let totalVenta = 0;
        let count = {{ old('inventarios') ? count(old('inventarios')) : count($Venta->venta_detalle) }};
        let cargado = false;
        let venta_detalle_id = 0;

        $(document).ready(function () {

            if (count > 0) {
                CalcularTotal();
                CalcularDebe();
            }

            $(document).on('shown.bs.select', '#inventario_id', function () {
                if (!cargado) {
                    listadoSelect();
                    cargado = true;
                }
            });

            $(document).on('hidden.bs.select', '#inventario_id', function () {
                cargado = false;
            });

            document.getElementById('cantidad').addEventListener('keydown', function (event) {
                // Verificar si la tecla presionada es 'Enter'
                if (event.key === 'Enter') {
                    event.preventDefault(); // Prevenir el envío del formulario
                    AgregarVentaDetalle();
                }
            });

            $('#inventario_id').change(function () {
                idInventario = $(this).val();
                $('#cantidad').val('');
                $('#total').val('');
                if (idInventario != '') {
                    $('#existencia').val($(`#option_${idInventario}`).data('existencia'));
                    $('#precio_venta').val($(`#option_${idInventario}`).data('precio'));
                } else {
                    $('#existencia').val('');
                    $('#precio_venta').val('');
                }
            });

            $('#cantidad').keyup(function () {
                const precio = $('#precio_venta').val() != '' ? $('#precio_venta').val() : 0;
                const cantidad = $(this).val() != '' ? $(this).val() : 0;

                const total = (precio * 1) * (cantidad * 1);
                $('#total').val(total);
            });

            $('#pagara').click(function () {
                if ($(this).is(':checked')) {
                    $('#formasDePago').collapse('show')
                } else {
                    $('#formasDePago').collapse('hide')
                }
            });

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

        const listadoSelect = () => {
            SwalLoading('Buscando productos...');

            $.ajax({
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                type: 'POST',
                url: "{{ route('inventario.listado_select') }}",
                dataType: 'json',
                success: function (response) {
                    Swal.close();

                    const select = $('#inventario_id');

                    // 🔥 Destruir el selectpicker
                    select.selectpicker('destroy');

                    // Limpiar y establecer nuevas opciones
                    select.empty().append(response.options);

                    // 🔥 Reactivar el selectpicker
                    select.selectpicker().selectpicker('toggle');
                }, error: function (error) {
                    SwalAlert(error.status, error.message);
                }
            })
        }

        const AgregarVentaDetalle = () => {
            const nombre = $(`#option_${idInventario}`).data('nombre');
            const precio = $('#precio_venta').val();
            const existencia = parseFloat($('#existencia').val());
            const cantidad = parseFloat($('#cantidad').val());
            const total = $('#total').val();
            let flag = true;

            if (idInventario == '') {
                SwalToast('Seleccione un producto', 'warning', 3000, 'top-center');
                return;
            } else if (Number.isNaN(cantidad)) {
                SwalToast('Especifique una cantidad', 'warning', 3000, 'top-center');
                return;
            } else if (cantidad < 1) {
                SwalToast('Campo cantidad debe ser mayor a 0', 'warning', 3000, 'top-center');
                return;
            } else if (cantidad > existencia) {
                SwalToast('Campo cantidad no debe ser mayor a las existencias', 'warning', 3000, 'top-center');
                return;
            } else if (count > 0) {
                $('.inventarios').each(function () {
                    if ($(this).val() == idInventario) {
                        SwalToast('Este producto ya está agregado', 'warning', 3000, 'top-center');
                        flag = false;
                        return;
                    }
                });
            }

            if (!flag)
                return

            const trProducto = '' +
                `<tr id="trProducto${count}">` +
                `<td>${nombre}` +
                `<input type="hidden" name="inventarios[]" id="inventarioInput_${count}" class="inventarios inventario${count}" value="${idInventario}">` +
                `<input type="hidden" name="nombres[]"  value="${nombre}">` +
                `<input type="hidden" name="venta_detalle_id[]" id="venta_detalle_id_${count}">` +
                `</td>` +
                `<td>$${precio}<input type="hidden" name="precios[]" value=${precio}></td>` +
                `<td>${cantidad}<input type="hidden" name="cantidades[]" id="cantidadInput_${count}" value="${cantidad}"></td>` +
                `<td>$${total}<input type="hidden" name="totales[]" class="totales" value="${total}"></td>` +
                `<td class="text-center"><button onclick="EliminarVentaDetalle(${count})" type="button" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></td>` +
                '</tr>';
            venta_detalle_id = 0;
            ActualizarInventario('-1', cantidad, idInventario, trProducto);
        }

        const EliminarVentaDetalle = (index) => {
            venta_detalle_id = $(`#venta_detalle_id_${index}`).val();
            ActualizarInventario(index);
        }

        const CalcularTotal = () => {
            totalVenta = 0;
            $('.totales').each(function () {
                totalVenta += parseFloat($(this).val());
            })

            $('#inputTotalVenta').val(totalVenta);
            $('#spanTotal').html(totalVenta.toFixed(2));
        }

        const CalcularCambio = () => {
            let efectivo = $('#efectivo').val() != '' ? $('#efectivo').val() * 1 : 0;
            let pagoCon = $('#pago_con').val() != '' ? $('#pago_con').val() * 1 : 0;
            let cambio = pagoCon - efectivo;
            $('#cambio').val(cambio);
        }

        const CalcularDebe = () => {
            let totalPagado = {{ $Venta->ultimo_pago }};
            let faltante = 0;
            $('.formasPagos').each(function () {
                totalPagado += $(this).val() != '' ? $(this).val() * 1 : 0;
            });
            faltante = totalVenta - totalPagado;

            $('#inputTotalPagado').val(totalPagado);
            $('#inputTotalFaltante').val(faltante);

            $('#spanPagado').html(totalPagado.toFixed(2));
            $('#spanFaltante').html(faltante.toFixed(2));
        }

        const ActualizarInventario = (index, cant = '', idInv = '', trProducto = '') => {
            SwalLoading();

            const cantidad = cant === '' ? $(`#cantidadInput_${index}`).val() : cant;
            const idInventario = idInv === '' ? $(`#inventarioInput_${index}`).val() : idInv;

            $.ajax({
                data: {
                    '_token': "{{ csrf_token() }}",
                    index,
                    cantidad,
                    idInventario,
                    venta_id,
                    venta_detalle_id,
                    isEdit: true
                },
                type: 'POST',
                url: '{{ route('inventario.actualizar_inventario') }}',
                dataType: 'json',
                success: function (response) {
                    if (response.status == 'success') {
                        if (index != '-1') {
                            $(`#trProducto${index}`).remove();
                            CalcularTotal();
                            CalcularDebe();
                            SwalToast('Producto quitado', 'success', 3000, 'top-center');
                        } else {
                            $('#tBodyVentaDetalle').prepend(trProducto);
                            $(`#venta_detalle_id_${count}`).val(response.venta_detalle_id);
                            count++;
                            CalcularTotal();
                            CalcularDebe();
                            SwalToast('Producto agregado', 'success', 3000, 'top-center');
                        }
                    } else {
                        SwalAlert(response.status, response.message);
                    }
                }, error: function (error) {
                    SwalAlert('error', 'Ha ocurrido un error', error.responseJSON.message);
                }
            })
        }

    </script>
@endpush
