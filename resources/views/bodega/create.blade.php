@extends('template')

@section('title','Crear Bodega')

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
        <h1 class="mt-4">Crear Nuevo Bodega</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{route('bodega.index')}}">Bodega</a></li>
            <li class="breadcrumb-item active">Crear Bodega</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{route('bodega.store')}}" method="post" autocomplete="off">
                @csrf
                <div class="row g-3">
                    <!-- Tienda -->
                    <x-form-element id="tienda_id" type="select" :params="$Tiendas" colSize="6"
                                    required="true"></x-form-element>

                    <!-- Nombre -->
                    <x-form-element id="nombre" required="true" focused="true" colSize="6"></x-form-element>

                    <!-- Ubicación -->
                    <x-form-element id="ubicacion" type="textarea"></x-form-element>

                    <!-- Botones -->
                    <x-form-buttons routeName="bodega"></x-form-buttons>

                </div>
            </form>
        </div>
    </div>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
@endpush
