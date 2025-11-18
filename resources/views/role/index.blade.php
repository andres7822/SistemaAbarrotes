@extends('template')

@section('title', 'Roles')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Roles</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item active">Roles</li>
        </ol>

        <div class="mb-4">
            <a href="{{route('role.create')}}" class="btn btn-success">Nuevo</a>
            {{--<a href="#" class="btn btn-warning">Editar</a>--}}
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla Roles
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($Roles as $Role)
                        <tr>
                            <td>{{ $Role->id }}</td>
                            <td>{{ $Role->name }}</td>
                            <x-action-buttons routeName="role" :params="$Role"></x-action-buttons>
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
