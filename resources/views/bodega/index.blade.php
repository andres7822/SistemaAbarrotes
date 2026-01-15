@extends('template')

@section('title','Bodegas')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Bodegas</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item active">Bodegas</li>
        </ol>

        <x-action-buttons-head routeName="bodega"></x-action-buttons-head>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla Bodegas
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>Tienda</th>
                        <th>Nombre</th>
                        <th>Ubicación</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($Bodegas as $Bodega)
                        <tr>
                            <!-- id -->
                            <td>{{ $Bodega->id }}</td>
                            <!-- Tienda -->
                            <td>{{ $Bodega->tienda->nombre }}</td>
                            <!-- Nombre -->
                            <td>{{ $Bodega->nombre }}</td>
                            <!-- Ubicacion -->
                            <td>{{ $Bodega->ubicacion ?? '' }}</td>
                            <!-- Estado -->
                            <td class="text-center">
                                @if($Bodega->estado == 1)
                                    <span class="fw-bolder rounded p-1 bg-success text-white">ACTIVO</span>
                                @else
                                    <span class="fw-bolder rounded p-1 bg-danger text-white">ELIMINADO</span>
                                @endif
                            </td>
                            <!--BOTONES ACCION-->
                            <x-action-buttons routeName="bodega" :params="$Bodega" restaurar="true">
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
