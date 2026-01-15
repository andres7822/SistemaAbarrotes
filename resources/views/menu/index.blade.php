@extends('template')

@section('title','Menus')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Menus</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item active">Menus</li>
        </ol>

        <x-action-buttons-head routeName="menu"></x-action-buttons-head>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla Menus
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Prioridad</th>
                        <th>Icono</th>
                        <th>Tipo Menu</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($Menus as $Menu)
                        <tr>
                            <!-- id -->
                            <td>{{ $Menu->id }}</td>
                            <!-- Nombre -->
                            <td>{{ $Menu->nombre }}</td>
                            <!-- Descripción -->
                            <td>{{ $Menu->descripcon ?? '' }}</td>
                            <!-- Prioridad -->
                            <td>{{ $Menu->prioridad }}</td>
                            <!-- Icono -->
                            <td>
                                {{ $Menu->icono->nombre }}
                            </td>
                            <!-- Tipo Menu -->
                            <td>
                                {{ $Menu->tipo_menu->nombre }}
                                @if(in_array($Menu->tipo_menu->id, [3,4]))
                                    <p class="opacity-50">{{ $Menu->menu->nombre }}</p>
                                @endif
                            </td>
                            <!--BOTONES ACCION-->
                            <x-action-buttons routeName="menu" :params="$Menu">
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
