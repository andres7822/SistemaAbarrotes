@extends('template')

@section('title','Subcategorias')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Subcategorias</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item active">Subcategorias</li>
        </ol>

        <div class="mb-4">
            <a href="{{route('subcategoria.create')}}" class="btn btn-success">Nuevo</a>
            {{--<a href="#" class="btn btn-warning">Editar</a>--}}
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla Subcategorias
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>Nombre</th>
                        <th>Categoria</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($Subcategorias as $Subcategoria)
                        <tr>
                            <!-- id -->
                            <td>{{ $Subcategoria->id }}</td>
                            <!-- Nombre -->
                            <td>{{ $Subcategoria->nombre }}</td>
                            <!-- Categoria -->
                            <td>{{ $Subcategoria->categoria->nombre }}</td>
                            <!-- Estado -->
                            <td class="text-center">
                                @if($Subcategoria->estado == 1)
                                    <span class="fw-bolder rounded p-1 bg-success text-white">ACTIVO</span>
                                @else
                                    <span class="fw-bolder rounded p-1 bg-danger text-white">ELIMINADO</span>
                                @endif
                            </td>
                            <!--BOTONES ACCION-->
                            <x-action-buttons routeName="subcategoria" :params="$Subcategoria" restaurar="true">
                            </x-action-buttons>

                        </tr>

                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection

@push('js')

@endpush
