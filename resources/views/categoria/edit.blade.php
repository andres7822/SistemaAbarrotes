@extends('template')

@section('title','Editar Categoria')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Editar Categoria: {{$Categoria->nombre}}</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{route('categoria.index')}}">Categorias</a></li>
            <li class="breadcrumb-item active">Editar Categoria</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{route('categoria.update', ['categoria' => $Categoria])}}" method="post" autocomplete="off">
                @method('PUT')
                @csrf
                {{--PARA ENVIAR FORMULARIOS--}}
                <div class="row g-3">
                    <!-- Nombre -->
                    <x-form-element id="nombre" required="true" isEdit="true" focused="true"
                                    value="{{$Categoria->nombre}}"></x-form-element>

                    <!-- Botones -->
                    <x-form-buttons routeName="categoria" isEdit="true"></x-form-buttons>

                </div>
            </form>
        </div>

    </div>

@endsection

@push('js')

@endpush
