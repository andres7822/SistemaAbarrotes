@extends('template')

@section('title', 'Crear Menu')

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
        <h1 class="mt-4">Crear Nuevo Tarjeta</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{route('menu.index')}}">Menu</a></li>
            <li class="breadcrumb-item active">Crear Menú</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{route('menu.store')}}" method="post" autocomplete="off">
                @csrf
                <div class="row g-3">
                    <!-- Nombre -->
                    <x-form-element id="nombre" required="true" focused="true"></x-form-element>

                    <!-- Descripción -->
                    <x-form-element id="descripcion" type="textarea"></x-form-element>

                    <!-- Icono -->
                    <div class="col-md-12">
                        <font color="red">*</font><label for="icono_id" class="form-label">Icono</label>
                        <select name="icono_id" id="icono_id" class="form-control selectpicker show-tick"
                                data-live-search="true" data-size="5">
                            <option value="" selected>SELECCIONE UNA OPCIÓN</option>
                            @foreach($Iconos as $Icono)
                                <option value="{{ $Icono->id }}"
                                        data-content="{{ $Icono->nombre }}" {{ $Icono->id == old('icono_id') ? 'selected' : '' }}>
                                    {{ $Icono->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('icono_id')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror

                    <!-- Tipo Menu -->
                    <x-form-element id="tipo_menu_id" type="select" :params="$TipoMenus"
                                    required="true"></x-form-element>

                    <!-- Menus -->
                    <x-form-element classDiv="collapse {{ old('tipo_menu_id') == 3 ? 'show' : '' }} collapseMenu"
                                    id="menu_id"
                                    type="select" :params="$Menus"
                                    required="true">
                    </x-form-element>

                    <!-- Botones -->
                    <x-form-buttons routeName="menu"></x-form-buttons>

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
                MostrarMenus($(this).val())
            })
        })

        const MostrarMenus = (index) => {
            if (index == 3) {
                $('.collapseMenu').collapse('show');
            } else {
                $('.collapseMenu').collapse('hide');
            }
        }
    </script>
@endpush
