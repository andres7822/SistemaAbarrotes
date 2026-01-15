@extends('template')

@section('title','Editar Presentacione')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Editar Presentacione: {{$Presentacione->nombre}}</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{route('presentacione.index')}}">Presentaciones</a></li>
            <li class="breadcrumb-item active">Editar Presentacione</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{route('presentacione.update', ['presentacione' => $Presentacione])}}" method="post"
                  autocomplete="off">
                @method('PUT')
                @csrf
                {{--PARA ENVIAR FORMULARIOS--}}
                <div class="row g-3">
                    <!-- Nombre -->
                    <x-form-element id="nombre" required="true" focused="true"
                                    value="{{$Presentacione->nombre}}"></x-form-element>

                    <!-- Clave -->
                    <x-form-element id="clave" required="true" value="{{$Presentacione->clave}}"></x-form-element>

                    <!-- Botones -->
                    <x-form-buttons routeName="presentacione" isEdit="true"></x-form-buttons>

                </div>
            </form>
        </div>

    </div>

@endsection

@push('js')

@endpush
