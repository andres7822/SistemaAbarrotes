@extends('template')

@section('title','Editar Inventario')

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
        <h1 class="mt-4">Editar Inventario: {{$Inventario->nombre}}</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{route('inventario.index')}}">Inventarios</a></li>
            <li class="breadcrumb-item active">Editar Inventario</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{route('inventario.update', ['inventario' => $Inventario])}}" method="post"
                  autocomplete="off">
                @method('PUT')
                @csrf
                {{--PARA ENVIAR FORMULARIOS--}}
                <div class="row g-3">
                    <!-- Existencia -->
                    <x-form-element id="existencia" type="number" required="true" focused="true"
                                    value="{{ $Inventario->existencia }}" colSize="4"></x-form-element>

                    <!-- Productos -->
                    <x-form-element id="producto_id" type="select" required="true" :params="$Productos"
                                    value="{{ $Inventario->producto_id }}" colSize="4"></x-form-element>

                    <!-- Bodegas -->
                    <x-form-element id="bodega_id" type="select" required="true" :params="$Bodegas"
                                    value="{{ $Inventario->bodega_id }}" colSize="4"></x-form-element>

                    <x-form-element id="observaciones" type="textarea"
                                    value="{{ $Inventario->observaciones }}"></x-form-element>

                    <!-- Botones -->
                    <x-form-buttons routeName="inventario" isEdit="true"></x-form-buttons>

                </div>
            </form>
        </div>

    </div>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
@endpush
