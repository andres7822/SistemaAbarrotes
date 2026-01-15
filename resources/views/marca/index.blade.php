@extends('template')

@section('title','Marcas')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Marcas</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item active">Marcas</li>
        </ol>

        <x-action-buttons-head routeName="marca"></x-action-buttons-head>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla Marcas
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($Marcas as $Marca)
                        <tr>
                            <!-- id -->
                            <td>{{$Marca->id}}</td>
                            <!-- Nombre -->
                            <td>{{$Marca->nombre}}</td>
                            <!-- Estado -->
                            <td class="text-center">
                                @if($Marca->estado == 1)
                                    <span class="fw-bolder rounded p-1 bg-success text-white">ACTIVO</span>
                                @else
                                    <span class="fw-bolder rounded p-1 bg-danger text-white">ELIMINADO</span>
                                @endif
                            </td>
                            <!--BOTONES ACCION-->
                            <x-action-buttons routeName="marca" :params="$Marca" restaurar="true">
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
