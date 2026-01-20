@extends('template')

@section('title','Editar Categoria')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">
        {{--<h1 class="mt-4">Editar Categoria: {{$Categoria->nombre}}</h1>--}}
        <h1 class="mt-4">Editar Categoria</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{route('categoria.index')}}">Categorias</a></li>
            <li class="breadcrumb-item active">Editar Categoria</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{route('categoria.update', ['categoria' => $Categorias[0]])}}" method="post"
                  autocomplete="off">
                @method('PUT')
                @csrf
                {{--PARA ENVIAR FORMULARIOS--}}
                <hr style="border-top: 3px solid black;">
                @foreach($Categorias as $index => $Categoria)
                    <h3 class="mt-4">{{ $Categoria->nombre }}</h3>
                    <div class="row g-3">
                        @if(old('nombre'))
                            {{ old('nombre')[0] }}
                        @endif
                        <!-- Nombre -->
                        <x-form-element id="nombre{{ $Categoria->id }}" name="nombre[]" label="Nombre" required="true"
                                        focused="true"
                                        value="{{$Categoria->nombre}}"></x-form-element>
                    </div>
                    <hr style="border: 3px solid black;">
                @endforeach
                <!-- Botones -->
                <x-form-buttons routeName="categoria" isEdit="true"></x-form-buttons>
            </form>
        </div>

    </div>

@endsection

@push('js')

@endpush
