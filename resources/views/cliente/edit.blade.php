@extends('template')

@section('title','Editar Cliente')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Editar Cliente: {{$Cliente->nombre}}</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{route('cliente.index')}}">Clientes</a></li>
            <li class="breadcrumb-item active">Editar Cliente</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{route('cliente.update', ['cliente' => $Cliente])}}" method="post" autocomplete="off">
                @method('PUT')
                @csrf
                {{--PARA ENVIAR FORMULARIOS--}}
                <div class="row g-3">
                    <!-- Nombre -->
                    <x-form-element id="nombre" required="true" focused="true" value="{{ $Cliente->nombre }}"
                                    colSize="6"></x-form-element>

                    <!-- Sexo -->
                    <x-form-element id="sexo_id" type="select" :params="$Sexos" value="{{ $Cliente->sexo_id }}"
                                    colSize="6"></x-form-element>

                    <!-- Domicilio -->
                    <x-form-element id="domicilio" type="textarea" value="{{ $Cliente->domicilio }}"></x-form-element>

                    <!-- Telefono Celular -->
                    <x-form-element id="telefono_celular" value="{{ $Cliente->telefono_celular }}"
                                    colSize="4"></x-form-element>

                    <!-- Correo Electrónico -->
                    <x-form-element id="correo_electronico" type="email" value="{{ $Cliente->correo_electronico }}"
                                    colSize="4"></x-form-element>

                    <!-- Fecha Nacimiento -->
                    <x-form-element id="fecha_nacimiento" type="date" value="{{ $Cliente->fecha_nacimiento }}"
                                    colSize="4"></x-form-element>

                    <!-- Botones -->
                    <x-form-buttons routeName="cliente" isEdit="true"></x-form-buttons>

                </div>
            </form>
        </div>

    </div>

@endsection

@push('js')

@endpush
