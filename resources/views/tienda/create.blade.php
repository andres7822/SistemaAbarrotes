@extends('template')

@section('title','Crear Tienda')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Crear Nuevo Tienda</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{route('tienda.index')}}">Tienda</a></li>
            <li class="breadcrumb-item active">Crear Tienda</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{route('tienda.store')}}" method="post" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <!-- Nombre -->
                    <x-form-element id="nombre" required="true" focused="true" colSize="6"></x-form-element>

                    <!-- Domicilio -->
                    <x-form-element id="domicilio" colSize="6"></x-form-element>

                    <!-- Descripción -->
                    <x-form-element id="descripcion" type="textarea"></x-form-element>

                    <!-- Imagen -->
                    <x-form-element id="imagen" type="file" accept="image/*"></x-form-element>

                    <!-- Encabezado Ticket -->
                    <x-form-element id="encabezado_ticket" type="textarea"></x-form-element>

                    <!-- Pie Ticket -->
                    <x-form-element id="pie_ticket" type="textarea"></x-form-element>

                    <!-- Botones -->
                    <x-form-buttons routeName="tienda"></x-form-buttons>

                </div>
            </form>
        </div>
    </div>

@endsection

@push('js')

@endpush
