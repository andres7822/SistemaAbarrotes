@extends('template')

@section('title','Crear Inventario')

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
        <h1 class="mt-4">Crear Nuevo Inventario</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{route('inventario.index')}}">Inventario</a></li>
            <li class="breadcrumb-item active">Crear Inventario</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{route('inventario.store')}}" method="post" autocomplete="off">
                @csrf
                <div class="row g-3">
                    <!-- Existencia -->
                    <x-form-element id="existencia" type="number" required="true" focused="true"
                                    colSize="4"></x-form-element>

                    <!-- Productos -->
                    <x-form-element id="producto_id" type="select" required="true" :params="$Productos"
                                    colSize="4"></x-form-element>

                    <!-- Bodegas -->
                    <x-form-element id="bodega_id" type="select" required="true" :params="$Bodegas"
                                    colSize="4"></x-form-element>

                    <x-form-element id="observaciones" type="textarea"></x-form-element>

                    <!-- Botones -->
                    <x-form-buttons routeName="inventario"></x-form-buttons>

                </div>
            </form>
        </div>
    </div>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
@endpush
