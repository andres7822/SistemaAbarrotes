@extends('template')

@section('title','Clientes')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Clientes</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item active">Clientes</li>
        </ol>

        <x-action-buttons-head routeName="cliente"></x-action-buttons-head>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla Clientes
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>Nombre</th>
                        <th>Sexo</th>
                        <th>Domicilio</th>
                        <th>Telefono Celular</th>
                        <th>Correo Electrónico</th>
                        <th>Fecha Nacimiento</th>
                        <th>Fecha Registro</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($Clientes as $Cliente)
                        <tr>
                            <!-- id -->
                            <td>{{ $Cliente->id }}</td>
                            <!-- Nombre -->
                            <td>{{ $Cliente->nombre }}</td>
                            <!-- Sexo -->
                            <td>
                                @if($Cliente->sexo)
                                    {{ $Cliente->sexo->nombre }}
                                @endif
                            </td>
                            <!-- Domicilio -->
                            <td>{{ $Cliente->domicilio ?? '' }}</td>
                            <!-- Telefono Celular -->
                            <td>{{ $Cliente->telefono_celular ?? '' }}</td>
                            <!-- Correo Electrónico -->
                            <td>{{ $Cliente->correo_electronico ?? '' }}</td>
                            <!-- Fecha Nacimiento -->
                            <td>
                                @if($Cliente->fecha_nacimiento)
                                    {{ \Carbon\Carbon::parse($Cliente->fecha_nacimiento)->format('d-m-Y') }}
                                @endif
                            </td>
                            <!-- Fecha Registro -->
                            <td>{{ \Carbon\Carbon::parse($Cliente->fecha_registro)->format('d-m-Y H:i:s') }}</td>
                            <!-- Estado -->
                            <td class="text-center">
                                @if($Cliente->estado == 1)
                                    <span class="fw-bolder rounded p-1 bg-success text-white">ACTIVO</span>
                                @else
                                    <span class="fw-bolder rounded p-1 bg-danger text-white">ELIMINADO</span>
                                @endif
                            </td>
                            <!--BOTONES ACCION-->
                            <x-action-buttons routeName="cliente" :params="$Cliente" restaurar="true">
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
