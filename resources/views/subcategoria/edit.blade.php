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
        $(document).ready(function () {
            $('#categoria_id').change(function () {
                idCategoria = $(this).val();
                MostrarNombreCategoria();
            });
        })

        const MostrarNombreCategoria = () => {
            if (idCategoria == '-1') {
                $('#collapseNombreCategoria').collapse('show');
            } else {
                $('#collapseNombreCategoria').collapse('hide');
            }
        }
    </script>
@endpush
