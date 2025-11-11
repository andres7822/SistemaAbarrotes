@extends('template')

@section('title', 'Menus')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Menus</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item active">Menus</li>
        </ol>

        <div class="mb-4">
            <a href="{{route('menu.create')}}" class="btn btn-success">Nuevo</a>
            {{--<a href="#" class="btn btn-warning">Editar</a>--}}
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla Menús
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
                        <th>Tipo Menú</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($Menus as $Menu)
                        <tr>
                            <td>{{ $Menu->id }}</td>
                            <td>{{ $Menu->nombre }}</td>
                            <td>{{ $Menu->descripcion }}</td>
                            <td>{{ $Menu->prioridad ?? '' }}</td>
                            <td>
                                <i class="{{ $Menu->icono->nombre }}"></i>
                                {{ $Menu->icono->nombre }}
                            </td>
                            <td>
                                {{ $Menu->tipo_menu->nombre }}
                                @if($Menu->tipo_menu_id == 3 || $Menu->tipo_menu_id == 4)
                                    <p class="opacity-50">
                                        {{$Menu->menu->nombre}}
                                    </p>
                                @endif
                            </td>
                            <x-action-buttons routeName="menu" :params="$Menu"></x-action-buttons>
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
