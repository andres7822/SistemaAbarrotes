@extends('template')

@section('title','Editar Bodega')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Editar Bodega: {{$Bodega->nombre}}</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{route('bodega.index')}}">Bodegas</a></li>
            <li class="breadcrumb-item active">Editar Bodega</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{route('bodega.update', ['bodega' => $Bodega])}}" method="post" autocomplete="off">
                @method('PUT')
                @csrf
                {{--PARA ENVIAR FORMULARIOS--}}
                <div class="row g-3">
                    <!-- Tienda -->
                    <x-form-element id="tienda_id" type="select" :params="$Tiendas" colSize="6"
                                    required="true" value="{{ $Bodega->tienda_id }}" colSize="6"></x-form-element>

                    <!-- Nombre -->
                    <x-form-element id="nombre" required="true" isEdit="true" focused="true"
                                    value="{{$Bodega->nombre}}" colSize="6"></x-form-element>

                    <!-- Ubicación -->
                    <x-form-element id="ubicacion" type="textarea" value="{{ $Bodega->ubicacion }}"></x-form-element>

                    <!-- Botones -->
                    <x-form-buttons routeName="bodega" isEdit="true"></x-form-buttons>

                </div>
            </form>
        </div>

    </div>

@endsection

@push('js')

@endpush
