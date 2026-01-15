@extends('template')

@section('title','Inventarios')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Inventarios</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item active">Inventarios</li>
        </ol>

        <x-action-buttons-head routeName="inventario" pdfFile="true"></x-action-buttons-head>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla Inventarios
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>Producto</th>
                        <th>Existencia</th>
                        <th>Observaciones</th>
                        <th>Bodega</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($Inventarios as $Inventario)
                        <tr>
                            <!-- id -->
                            <td>{{ $Inventario->id }}</td>
                            <!-- Producto -->
                            <td>{{ $Inventario->producto->nombre }}</td>
                            <!-- Existencia -->
                            <td>{{ $Inventario->existencia }}</td>
                            <!-- Observaciones -->
                            <td>{{ $Inventario->observaciones ?? '' }}</td>
                            <!-- Bodega -->
                            <td>{{ $Inventario->bodega->nombre }}</td>
                            <!-- Imagen -->
                            <td>
                                @if($Inventario->producto->imagen)
                                    <a onclick="ImagenModal({{ json_encode($Inventario->producto) }})" href="#/">
                                        <img height="150" width="150"
                                             src="{{ Storage::url('public/productos/' . $Inventario->producto->imagen) }}"
                                             alt="{{ $Inventario->producto->imagen }}"
                                        >
                                    </a>
                                @endif
                            </td>
                            <!--BOTONES ACCION-->
                            <x-action-buttons routeName="inventario" :params="$Inventario">
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
