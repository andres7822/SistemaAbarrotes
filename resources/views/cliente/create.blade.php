@extends('template')

@section('title','Crear Cliente')

@push('css')
    <style>
        textarea {
            resize: none;
        }
    </style>
@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Crear Nuevo Cliente</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{route('cliente.index')}}">Cliente</a></li>
            <li class="breadcrumb-item active">Crear Cliente</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{route('cliente.store')}}" method="post" autocomplete="off">
                @csrf
                <div class="row g-3">
                    <!-- Nombre -->
                    <x-form-element id="nombre" required="true" focused="true" colSize="6"></x-form-element>

                    <!-- Sexo -->
                    <x-form-element id="sexo_id" type="select" :params="$Sexos" colSize="6"></x-form-element>

                    <!-- Domicilio -->
                    <x-form-element id="domicilio" type="textarea"></x-form-element>

                    <!-- Telefono Celular -->
                    <x-form-element id="telefono_celular" colSize="4"></x-form-element>

                    <!-- Correo Electrónico -->
                    <x-form-element id="correo_electronico" type="email" colSize="4"></x-form-element>

                    <!-- Fecha Nacimiento -->
                    <x-form-element id="fecha_nacimiento" type="date" colSize="4"></x-form-element>

                    <!-- Botones -->
                    <x-form-buttons routeName="cliente"></x-form-buttons>

                </div>
            </form>
        </div>
    </div>

@endsection

@push('js')

@endpush
