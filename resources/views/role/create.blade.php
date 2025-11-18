@extends('template')

@section('title', 'Crear Roles')

@push('css')
    <style>
        /* styles.css */
        .table-container {
            position: relative;
            width: 100%;
            overflow: auto;
            height: 400px; /* Ajusta la altura según sea necesario */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
            white-space: nowrap; /* Evita que el contenido se ajuste automáticamente */
        }

        thead th {
            position: sticky;
            top: 0;
            background-color: #f2f2f2; /* Fondo para el encabezado */
            z-index: 2; /* Asegura que el encabezado esté por encima de las celdas */
        }

        tbody td:first-child,
        thead th:first-child {
            position: sticky;
            left: 0;
            background-color: #f2f2f2; /* Fondo para la primera columna */
            z-index: 1; /* Asegura que la primera columna esté por encima de las celdas */
        }

        thead th:first-child {
            z-index: 3; /* Asegura que la esquina superior izquierda esté por encima de todo */
        }

    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Crear Nuevo Rol</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{route('role.index')}}">Roles</a></li>
            <li class="breadcrumb-item active">Crear Role</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{route('role.store')}}" method="post" autocomplete="off">
                @csrf
                {{--PARA ENVIAR FORMULARIOS--}}
                <div class="row g-3">
                    <x-form-element id="name" label="Nombre" required="true" focused="true"></x-form-element>

                    <div class="table-container">
                        <table class="table">
                            <thead>
                            <tr>
                                <th></th>
                                @foreach($TypePermissions as $item)
                                    <th>
                                        <div class="text-center">
                                            <label for="checkPermission{{ $item->id }}">
                                                {{ $item->name }}
                                            </label><br>
                                            <input type="checkbox" id="checkPermission{{ $item->id }}" class="permiso"
                                                   value="{{ $item->id }}">
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($Menus as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" id="checkMenu{{ $item->id }}" class="menu"
                                               value="{{ $item->id }}">
                                        <label for="checkMenu{{ $item->id }}">
                                            {{ $item->nombre }}
                                        </label>
                                    </td>
                                    <!-- Ver -->
                                    <td>
                                        <div class="text-center">
                                            <input type="checkbox" name="permiso[]" class="ver"
                                                   data-idmenu="{{ $item->id }}"
                                                   data-idpermisison="1" value="{{ $item->id }}_1"
                                                {{ in_array($item->id.'_1', (old('permiso') ?? [])) ? 'checked' : '' }}
                                            >
                                        </div>
                                    </td>
                                    <!-- Crear -->
                                    <td>
                                        <div class="text-center">
                                            <input type="checkbox" name="permiso[]" class="crear"
                                                   data-idmenu="{{ $item->id }}"
                                                   data-idpermisison="2" value="{{ $item->id }}_2"
                                                {{ in_array($item->id.'_2', (old('permiso') ?? [])) ? 'checked' : '' }}
                                            >
                                        </div>
                                    </td>
                                    <!-- Editar -->
                                    <td>
                                        <div class="text-center">
                                            <input type="checkbox" name="permiso[]" class="editar"
                                                   data-idmenu="{{ $item->id }}"
                                                   data-idpermisison="3" value="{{ $item->id }}_3"
                                                {{ in_array($item->id.'_3', (old('permiso') ?? [])) ? 'checked' : '' }}
                                            >
                                        </div>
                                    </td>
                                    <!-- Eliminar -->
                                    <td>
                                        <div class="text-center">
                                            <input type="checkbox" name="permiso[]" class="eliminar"
                                                   data-idmenu="{{ $item->id }}"
                                                   data-idpermisison="4" value="{{ $item->id }}_4"
                                                {{ in_array($item->id.'_4', (old('permiso') ?? [])) ? 'checked' : '' }}
                                            >
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @error('permiso')
                        <small class="text-danger">* {{ $message }}</small>
                        @enderror
                    </div>

                    <x-form-buttons routeName="role"></x-form-buttons>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            $('.permiso').click(function () {
                const seleccionadoPermiso = $(this).is(':checked');
                const idPermiso = $(this).val();
                $(`input[data-idpermisison="${idPermiso}"]`).prop('checked', seleccionadoPermiso);
            })

            $('.menu').click(function () {
                const seleccionadoMenu = $(this).is(':checked');
                const idMenu = $(this).val();
                $(`input[data-idmenu="${idMenu}"]`).prop('checked', seleccionadoMenu);
            })
        })
    </script>
@endpush
