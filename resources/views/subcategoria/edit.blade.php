@extends('template')

@section('title','Editar Subcategoria')

@push('css')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Editar Subcategoria: {{$Subcategoria->nombre}}</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{route('subcategoria.index')}}">Subcategorias</a></li>
            <li class="breadcrumb-item active">Editar Subcategoria</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{route('subcategoria.update', ['subcategoria' => $Subcategoria])}}" method="post"
                  autocomplete="off">
                @method('PUT')
                @csrf
                {{--PARA ENVIAR FORMULARIOS--}}
                <div class="row g-3">
                    <!-- Nombre -->
                    <x-form-element id="nombre" required="true" isEdit="true" focused="true"
                                    value="{{$Subcategoria->nombre}}"></x-form-element>

                    <!-- Categorias -->
                    <?php
                    $optionsExtraFirst = [
                        [
                            'value' => -1,
                            'selected' => old('categoria_id') == '-1' ? 'selected' : '',
                            'content' => 'Escribir categoria'
                        ]
                    ];
                    ?>
                    <x-form-element id="categoria_id" type="select" :params="$Categorias"
                                    value="{{ $Subcategoria->categoria_id }}" :optionsExtraFirst="$optionsExtraFirst"
                                    required="true" colSize="6">
                    </x-form-element>

                    <!-- Especificar categoria -->
                    <x-form-element idDiv="collapseNombreCategoria"
                                    classDiv="collapse {{old('categoria_id') == '-1' ? 'show' : ''}}"
                                    id="nombre_categoria"
                                    colSize="6" required="true">
                    </x-form-element>

                    <!-- Tipo Descuento -->
                    <x-form-element id="tipo_descuento_id" type="select" :params="$TipoDescuentos"
                                    value="{{ $Subcategoria->tipo_descuento_id }}"
                                    defaultTextOption="Ninguno...">
                    </x-form-element>

                    <!-- Especificar descuento -->
                    <x-form-element idDiv="collapseDescuento"
                                    classDiv="collapse {{ in_array(old('tipo_descuento_id'), ['', 3]) ? '' : 'show' }}"
                                    id="descuento" colSize="4" required="true">
                    </x-form-element>

                    <!-- Especificar piezas (lleva) -->
                    <x-form-element idDiv="collapsePiezas"
                                    classDiv="collapse {{ in_array(old('tipo_descuento_id'), [3,4,5]) ? 'show' : '' }}"
                                    id="piezas" colSize="4" required="true">
                    </x-form-element>

                    <!-- Especificar piezas (paga) -->
                    <x-form-element idDiv="collapsePaga"
                                    classDiv="collapse {{ in_array(old('tipo_descuento_id'), [3,5]) ? 'show' : '' }}"
                                    id="paga" colSize="4" required="true">
                    </x-form-element>

                    <small id="textoEjemplo" class="opacity-75"></small>

                    <!-- Botones -->
                    <x-form-buttons routeName="subcategoria" isEdit="true"></x-form-buttons>

                </div>
            </form>
        </div>

    </div>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script>

        let idCategoria = '';
        let tipo_descuento_id = '';

        $(document).ready(function () {
            $('#categoria_id').change(function () {
                idCategoria = $(this).val();
                MostrarNombreCategoria();
            });

            $('#tipo_descuento_id').change(function () {
                tipo_descuento_id = $(this).val();
                MostrarDescuento();
            })
        })

        const MostrarNombreCategoria = () => {
            if (idCategoria == '-1') {
                $('#collapseNombreCategoria').collapse('show');
            } else {
                $('#collapseNombreCategoria').collapse('hide');
            }
        }

        const MostrarDescuento = () => {
            switch (tipo_descuento_id) {
                case '1': //Porcentaje
                case '2': //Cantidad Fija
                    tipo_descuento_id == '1' ?
                        $('#textoEjemplo').html('EJ: Productos con 50% de descuento')
                        :
                        $('#textoEjemplo').html('EJ: Productos con $250 de descuento');
                    $('#collapsePaga').collapse('hide');
                    $('#collapsePiezas').collapse('hide');
                    $('#collapseDescuento').collapse('show');
                    break;
                case '3': //Pieza
                    $('#collapseDescuento').collapse('hide');
                    $('#collapsePaga').collapse('show');
                    $('#collapsePiezas').collapse('show');
                    $('#textoEjemplo').html('EJ: Llevate 3 piezas y paga 2');
                    break;
                case '4': //Mayoreo
                    $('#collapsePaga').collapse('hide');
                    $('#collapseDescuento').collapse('show');
                    $('#collapsePiezas').collapse('show');
                    $('#textoEjemplo').html('EJ: A partir de 5 productos hay un descuento de 20%');
                    break;
                case '5': //Pieza y porcentaje
                    $('#collapseDescuento').collapse('show');
                    $('#collapsePiezas').collapse('show');
                    $('#collapsePaga').collapse('show');
                    $('#textoEjemplo').html('EJ: Paga 4 y llevate el 5° a mitad de precio');
                    break;
                default:
                    $('#collapseDescuento').collapse('hide');
                    $('#collapsePaga').collapse('hide');
                    $('#collapsePiezas').collapse('hide');
                    $('#textoEjemplo').html('');
            }
        }
    </script>
@endpush
