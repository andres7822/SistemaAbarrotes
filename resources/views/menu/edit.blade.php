@extends('template')

@section('title','Editar Menu')

@push('css')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <style>
        textarea {
            resize: none;
        }
    </style>
@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Editar Menu: {{$Menu->nombre}}</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{route('menu.index')}}">Menus</a></li>
            <li class="breadcrumb-item active">Editar Menu</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{route('menu.update', ['menu' => $Menu])}}" method="post" autocomplete="off">
                @method('PUT')
                @csrf
                {{--PARA ENVIAR FORMULARIOS--}}
                <div class="row g-3">

                    <!-- Nombre -->
                    <x-form-element id="nombre" required="true" focused="true"
                                    value="{{ $Menu->nombre }}">
                    </x-form-element>

                    <!-- Descripcion -->
                    <x-form-element id="descripcion" type="textarea" value="{{ $Menu->descripcion }}">
                    </x-form-element>

                    <!-- Iconos -->
                    <x-form-element id="icono_id" type="select" required="true" :params="$Iconos"
                                    value="{{ $Menu->icono_id }}">
                    </x-form-element>

                    <!-- Tipo Menu -->
                    <x-form-element id="tipo_menu_id" type="select" required="true" :params="$TipoMenus"
                                    value="{{ $Menu->tipo_menu_id }}">
                    </x-form-element>

                    <!-- Menus -->
                    <x-form-element idDiv="collapseMenus"
                                    classDiv="collapse {{ old('tipo_menu_id', $Menu->tipo_menu_id) == 3 ? 'show' : '' }}"
                                    id="menu_id" type="select" required="true" :params="$Menus"
                                    value="{{ $Menu->menu_id }}">
                    </x-form-element>

                    <x-form-buttons routeName="menu" isEdit="true"></x-form-buttons>

                </div>
            </form>
        </div>

    </div>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#tipo_menu_id').change(function () {
                MostrarMenus($(this).val());
            })
        });

        const MostrarMenus = (index) => {
            if (index == 3) {
                $('#collapseMenus').collapse('show');
            } else {
                $('#collapseMenus').collapse('hide');
            }
        }
    </script>
@endpush
