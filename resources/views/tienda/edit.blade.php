@extends('template')

@section('title','Editar Tienda')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Editar Tienda: {{$Tienda->nombre}}</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{route('tienda.index')}}">Tiendas</a></li>
            <li class="breadcrumb-item active">Editar Tienda</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{route('tienda.update', ['tienda' => $Tienda])}}" method="post" autocomplete="off">
                @method('PUT')
                @csrf
                {{--PARA ENVIAR FORMULARIOS--}}
                <div class="row g-3">
                    <!-- Nombre -->
                    <x-form-element id="nombre" required="true" focused="true" colSize="6"
                                    value="{{$Tienda->nombre}}"></x-form-element>

                    <!-- Domicilio -->
                    <x-form-element id="domicilio" colSize="6" value="{{$Tienda->domicilio}}"></x-form-element>

                    <!-- Descripcion -->
                    <x-form-element id="descripcion" type="textarea" value="{{$Tienda->descripcion}}"></x-form-element>

                    <!-- Botones -->
                    <x-form-buttons routeName="tienda" isEdit="true"></x-form-buttons>

                </div>
            </form>
        </div>

    </div>

@endsection

@push('js')

@endpush
