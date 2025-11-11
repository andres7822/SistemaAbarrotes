@extends('template')

@section('title','Crear Categoria')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Crear Nuevo Categoria</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{route('categoria.index')}}">Categoria</a></li>
            <li class="breadcrumb-item active">Crear Categoria</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{route('categoria.store')}}" method="post" autocomplete="off">
                @csrf
                <div class="row g-3">
                    <!-- Nombre -->
                    <x-form-element id="nombre" required="true" focused="true"></x-form-element>

                    <!-- Botones -->
                    <x-form-buttons routeName="categoria"></x-form-buttons>

                </div>
            </form>
        </div>
    </div>

@endsection

@push('js')

@endpush
