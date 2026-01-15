@extends('template')

@section('title','Editar Producto')

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
        <h1 class="mt-4">Editar Producto: {{$Producto->nombre}}</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{route('producto.index')}}">Productos</a></li>
            <li class="breadcrumb-item active">Editar Producto</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{route('producto.update', ['producto' => $Producto])}}" method="post" autocomplete="off"
                  enctype="multipart/form-data">
                @method('PUT')
                @csrf
                {{--PARA ENVIAR FORMULARIOS--}}
                <div class="row g-3">
                    <!-- Nombre -->
                    <x-form-element id="nombre" required="true" isEdit="true" focused="true"
                                    value="{{ $Producto->nombre }}"></x-form-element>

                    <!-- Marca -->
                    <x-form-element id="marca_id" type="select" :params="$Marcas" value="{{ $Producto->marca_id }}"
                                    colSize="6">
                    </x-form-element>

                    <!-- Presenteación -->
                    <x-form-element id="presentacione_id" label="Presentación" type="select" :params="$Presentaciones"
                                    value="{{ $Producto->presentacione_id }}" colSize="6">
                    </x-form-element>

                    <!-- Categoria -->
                    <x-form-element id="categoria_id" type="select" :params="$Categorias"
                                    value="{{ $Producto->subcategoria->categoria_id }}" colSize="6" required="true">
                    </x-form-element>

                    <!-- Subategoria -->
                    <x-form-element id="subcategoria_id" type="select" :params="$Subcategorias"
                                    value="{{ $Producto->subcategoria_id }}" colSize="6" required="true">
                    </x-form-element>

                    <!-- Codigo de barras -->
                    <x-form-element id="codigo_barras" value="{{ $Producto->codigo_barras }}"></x-form-element>

                    <!-- Descripción -->
                    <x-form-element id="descripcion" type="textarea">{{ $Producto->descripcion }}</x-form-element>

                    <!-- Precio Venta -->
                    <x-form-element id="precio_venta" colSize="6" required="true"
                                    value="{{ $Producto->precio_venta }}"></x-form-element>

                    <!-- Costo -->
                    <x-form-element id="costo" colSize="6" required="true"
                                    value="{{ $Producto->costo }}"></x-form-element>

                    <!-- Imagen -->
                    <x-form-element id="imagen" type="file" accept="image/*"></x-form-element>

                    <!-- Botones -->
                    <x-form-buttons routeName="producto" isEdit="true"></x-form-buttons>

                </div>
            </form>
        </div>

    </div>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#categoria_id').change(function () {
                const idCategoria = $(this).val();
                if (idCategoria == '') {
                    $('#subcategoria_id').selectpicker('destroy').empty().append(
                        "<option value=''>SELECCIONE UNA CATEGORIA</option>"
                    ).selectpicker();
                    return;
                }
                SwalLoading("Cargando subcategorias");
                $.ajax({
                    data: {
                        "_token": "{{ csrf_token() }}",
                        idCategoria
                    },
                    type: 'POST',
                    url: '{{ route('subcategoria.subcategoria_by_cat') }}',
                    dataType: 'json',
                    success: function (response) {
                        if (response.status == 'success') {
                            Swal.close();
                            $('#subcategoria_id').selectpicker('destroy').empty().append(response.options).selectpicker();
                        } else {
                            SwalAlert(response.status, response.message);
                        }
                    },
                    error: function (error) {
                        console.log('----- ERROR -----');
                        console.log(error.responseJSON.message);
                        SwalAlert('error', 'Ha ocurrido un error', error.responseJSON.message);
                    }
                });
            });
        });
    </script>
@endpush
