@extends('template')

@section('title','Crear Venta')

@push('css')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <style>
        textarea {
            resize: none;
        }
    </style>
@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">
            Crear Nuevo Venta<br>
            Folio: {{ $Venta->folio }}
        </h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{route('venta.index')}}">Venta</a></li>
            <li class="breadcrumb-item active">Crear Venta</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{route('venta.store')}}" method="post" autocomplete="off" id="formulario">
                @csrf
                <div class="row g-3">

                    <hr style="border-top: 3px solid black;">

                    <!-- Tipo Venta -->
                    {{--<x-form-element id="tipo_venta_id" type="select" label="Tipo De Venta" required="true"
                                    focused="true" :params="$TipoVentas" colSize="6"></x-form-element>--}}

                    <!-- Cliente -->
                    <x-form-element id="cliente_id" type="select" required="true" :params="$Clientes"></x-form-element>

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

                    <input type="hidden" id="venta_id" name="venta_id" value="{{ old('venta_id', $Venta->id) }}">

                    <hr style="border-top: 3px solid black;">

                    <div class="col-md-4">
                        <label class="form-check-label">Total Venta: $<span id="spanTotal">0.00</span></label>
                        <input type="hidden" name="inputTotalVenta" id="inputTotalVenta" value="0">
                    </div>

                    <div class="col-md-4">
                        <label class="form-check-label">Total Pagado: $<span id="spanPagado">0.00</span></label>
                        <input type="hidden" name="inputTotalPagado" id="inputTotalPagado" value="0">
                    </div>

                    <div class="col-md-4">
                        <label class="form-check-label">Faltante: $<span id="spanFaltante">0.00</span></label>
                        <input type="hidden" name="inputTotalFaltante" id="inputTotalFaltante" value="0">
                    </div>

                    <div class="form-check form-check-inline">
                        @if(count($Caja) > 0)
                            <input class="form-check-input" type="checkbox" id="pagara"
                                   name="pagara" {{ old('pagara') ? 'checked' : '' }}>
                            <label class="form-check-label" for="pagara">
                                Pagará
                            </label>
                        @else
                            <font color="red">No se ha detectado una caja abierta para pagar</font>
                        @endif
                    </div>

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
                                <th>Bodega</th>
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
                                        </td>
                                        <!-- Bodega -->
                                        <td>
                                            {{ old('bodegas')[$i] }}
                                            <input type="hidden" name="bodegas[]" value="{{ old('bodegas')[$i] }}">
                                        </td>
                                        <!-- Precio -->
                                        <td>
                                            ${{ number_format(old('precios')[$i], 2) }}
                                            <input type="hidden" name="precios[]" value="{{ old('precios')[$i] }}">
                                        </td>
                                        <!-- Cantidad -->
                                        <td>
                                            {{ old('cantidades')[$i] }}
                                            <input type="hidden" name="cantidades[]" id="cantidadInput_{{ $i }}"
                                                   value="{{ old('cantidades')[$i] }}">
                                        </td>
                                        <!-- totales -->
                                        <td>
                                            ${{ number_format(old('totales')[$i], 2) }}
                                            <input type="hidden" name="totales[]" class="totales"
                                                   value="{{ old('totales')[$i] }}">
                                        </td>
                                        <input type="hidden" name="venta_detalle_id[]" id="venta_detalle_id_{{ $i }}"
                                               value="{{ old('venta_detalle_id')[$i] }}">
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
                    {{--<x-form-buttons routeName="venta"></x-form-buttons>--}}
                    <div class="col-md-12 mb-2 text-center">
                        <button type="button" onclick="ValidarPagar('Continuar')" class="btn btn-success" name="accion"
                                value="continuar">
                            <i class="fa fa-save" aria-hidden="true"></i>
                            Guardar y continuar
                            <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        </button>
                        <button type="button" onclick="ValidarPagar('Regresar')" class="btn btn-primary" name="accion"
                                value="regresar">
                            <i class="fa fa-save" aria-hidden="true"></i>
                            Guardar y regresar
                            <i class="fa fa-undo" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="d-none col-md-12 mb-2 text-center">
                        <button type="submit" class="btn btn-success" name="accion" id="btnContinuar" value="continuar">
                            <i class="fa fa-save" aria-hidden="true"></i>
                            Guardar y continuar
                            <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        </button>
                        <button type="submit" class="btn btn-primary" name="accion" id="btnRegresar" value="regresar">
                            <i class="fa fa-save" aria-hidden="true"></i>
                            Guardar y regresar
                            <i class="fa fa-undo" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </form>
            <div class="text-center">
                <form action="{{route('venta.destroy', ['venta' => $Venta->id])}}" method="post">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger"><i class="fa fa-ban" aria-hidden="true"></i> Cancelar
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script>
        const venta_id = {{ old('venta_id', $Venta->id) }};
        let idInventario = '';
        let totalVenta = 0;
        let count = {{ old('inventarios') ? count(old('inventarios')) : 0 }};
        let cargado = false;
        let venta_detalle_id = 0;
        let totalPagado = 0;
        let faltante = 0;
        let cambio = 0;

        $(document).ready(function () {

            console.log(`venta_id: ${venta_id}`);

            if (count > 0) {
                CalcularTotal();
                CalcularDebe();
            }

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

            $('#cliente_id').change(function () {
                const cliente_id = $(this).val();
                SwalLoading();
                $.ajax({
                    data: {
                        "_token": "{{ csrf_token() }}",
                        venta_id,
                        cliente_id
                    },
                    type: 'POST',
                    url: "{{ route('venta.actualizar_cliente') }}",
                    dataType: 'json',
                    success: function (response) {
                        Swal.close();
                        if (response.status == 'error')
                            SwalAlert(error.status, error.message);
                    }, error: function (error) {
                        SwalAlert(error.status, error.message);
                    }
                });
            });

            $(document).on('shown.bs.select', '#inventario_id', function () {
                if (!cargado) {
                    listadoSelect();
                    cargado = true;
                }
            });

            $(document).on('hidden.bs.select', '#inventario_id', function () {
                cargado = false;
            });

            /*document.getElementById('cantidad').addEventListener('keydown', function (event) {
                // Verificar si la tecla presionada es 'Enter'
                if (event.key === 'Enter') {
                    event.preventDefault(); // Prevenir el envío del formulario
                    AgregarVentaDetalle();
                }
            });*/

            document.getElementById('formulario').addEventListener('keydown', function (event) {
                //Verificar la tecla enter
                if (event.key === 'Enter') {
                    event.preventDefault(); // Prevenir el envío del formulario
                    const inputHtml = event.target;
                    if (inputHtml.id == 'cantidad') {
                        AgregarVentaDetalle();
                    } else {
                        ValidarPagar('Continuar');
                    }
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
            })

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

                    //Destruir el selectpicker
                    select.selectpicker('destroy');

                    // Limpiar y establecer nuevas opciones
                    select.empty().append(response.options);

                    //Reactivar el selectpicker
                    select.selectpicker().selectpicker('toggle');
                }, error: function (error) {
                    SwalAlert(error.status, error.message);
                }
            })
        }

        const AgregarVentaDetalle = () => {
            const nombre = $(`#option_${idInventario}`).data('nombre');
            const bodega = $(`#option_${idInventario}`).data('bodega');
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
                `<input type="hidden" name="nombres[]" value="${nombre}">` +
                `</td>` +
                `<td>${bodega}<input type="hidden" name="bodegas[]" value="${bodega}"></td>` +
                `<td>$${precio}<input type="hidden" name="precios[]" value="${precio}"></td>` +
                `<td>${cantidad}<input type="hidden" class="cantidad${count}" name="cantidades[]" id="cantidadInput_${count}" value="${cantidad}"></td>` +
                `<td>$${total}<input type="hidden" name="totales[]" class="totales" value="${total}"></td>` +
                `<input type="hidden" name="venta_detalle_id[]" id="venta_detalle_id_${count}">` +
                `<td class="text-center"><button onclick="EliminarVentaDetalle(${count})" type="button" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></td>` +
                '</tr>';
            venta_detalle_id = 0;
            ActualizarInventario('-1', cantidad, idInventario, trProducto);

            const select = $('#inventario_id');

            //Destruir el selectpicker
            select.selectpicker('destroy');

            // Limpiar y establecer nuevas opciones
            select.empty().append('<option value="">SELECCIONE UNA OPCIÓN...</option>');

            //Reactivar el selectpicker
            select.selectpicker()
            idInventario = '';
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
            console.log('CalcularTotal');
            console.log(totalVenta);
            $('#inputTotalVenta').val(totalVenta);
            $('#spanTotal').html(totalVenta.toFixed(2));
        }

        const CalcularCambio = () => {
            let efectivo = $('#efectivo').val() != '' ? $('#efectivo').val() * 1 : 0;
            let pagoCon = $('#pago_con').val() != '' ? $('#pago_con').val() * 1 : 0;
            cambio = pagoCon - efectivo;
            $('#cambio').val(cambio);
        }

        const CalcularDebe = () => {
            totalPagado = 0;
            faltante = 0;
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

            console.log('cantidad:' + cantidad);
            console.log('idInventario:' + idInventario);

            $.ajax({
                data: {
                    '_token': "{{ csrf_token() }}",
                    index,
                    cantidad,
                    idInventario,
                    venta_id,
                    venta_detalle_id
                },
                type: 'POST',
                url: '{{ route('inventario.actualizar_inventario') }}',
                dataType: 'json',
                success: function (response) {
                    if (response.status == 'success') {
                        if (index != '-1') { //Quitar producto de la lista
                            $(`#trProducto${index}`).remove();
                            CalcularTotal();
                            CalcularDebe();
                            SwalToast('Producto quitado', 'success', 3000, 'top-center');
                        } else { //Agregar producto a la lista de la venta
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
                return false;
            }
            if (cambio < 0) {
                SwalToast('Campo efectivo debe ser menor o igual al campo "pago con"', 'warning', 3000, 'top');
                return false;
            }
            if (faltante < 0) {
                SwalToast('Total a pagar es mayor al valor de lo que debe', 'warning', 3000, 'top');
                return false;
            }
            if (faltante > 0) {
                SwalToast('Total a pagar es menor al total de la venta', 'warning', 3000, 'top');
                return false;
            }
            if (!(totalPagar > 0)) {
                SwalToast('Rellene los campos necesarios', 'warning', 3000, 'top');
                return false;
            }
            return true;
        }

        const ValidarPagar = (typeBtn) => {
            if ($('#pagara').is(':checked')) {
                if (CobrarVenta()) {
                    $(`#btn${typeBtn}`).trigger('click');
                }
            } else {
                $(`#btn${typeBtn}`).trigger('click');
            }
        }

    </script>
@endpush
