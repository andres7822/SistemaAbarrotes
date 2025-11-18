@extends('template')

@section('title', 'Editar Usuario')

@push('css')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Editar Usuario</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{route('user.index')}}">Usuarios</a></li>
            <li class="breadcrumb-item active">Editar Usuario</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{route('user.update', ['user' => $user])}}" method="post" autocomplete="off">
                @method('PUT')
                @csrf
                {{--PARA ENVIAR FORMULARIOS--}}
                <div class="row g-3">

                    <!-- Nombre -->
                    <x-form-element id="name" required="true" label="Nombre" colSize="6"
                                    value="{{ $user->name }}"></x-form-element>

                    <!-- Usuario -->
                    <x-form-element id="username" required="true" label="Nombre De Usuario"
                                    colSize="6" value="{{ $user->username }}"></x-form-element>

                    <!-- Email -->
                    <x-form-element id="email" type="email" label="Correo Electrónico"
                                    value="{{ $user->email }}"></x-form-element>

                    <!-- Password -->
                    <x-form-element id="password" type="password" required="true" label="Contraseña"
                                    colSize="6"></x-form-element>

                    <!-- Confirmr_Password -->
                    <x-form-element id="confirm_password" type="password" required="true"
                                    label="Confirmar Contraseña" colSize="6"></x-form-element>

                    <!-- Roles -->
                    <x-form-element type="select" id="role" label="Rol" required="true"
                                    :params="$Roles" value="{{ $user->roles->pluck('id')[0] }}"></x-form-element>

                    <!--BOTONES-->
                    <x-form-buttons routeName="user"></x-form-buttons>

                </div>
            </form>
        </div>

    </div>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
@endpush
