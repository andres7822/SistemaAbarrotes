@php use Illuminate\Support\Facades\Storage; @endphp
@extends('template')

@section('title', 'Productos')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Productos</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item active">Productos</li>
        </ol>

        <x-action-buttons-head routeName="producto"></x-action-buttons-head>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla Productos
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>Nombre</th>
                        <th>Marca</th>
                        <th>Presentación</th>
                        <th>
                            Subcategoria<br>
                            <span class="opacity-50">Categoria</span>
                        </th>
                        <th>Código De Barras</th>
                        <th>Descripción</th>
                        <th>Precio Venta</th>
                        <th>Costo</th>
                        <th>Imagen</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($Productos as $index => $Producto)
                            <?php
                            $Productos[$index]['url'] = Storage::url('public/productos/' . $Producto->imagen);
                            ?>
                        <tr>
                            <!-- id -->
                            <td>{{ $Producto->id }}</td>
                            <!-- Nombre -->
                            <td>{{ $Producto->nombre }}</td>
                            <!-- Marca -->
                            <td>{{ $Producto->marca->nombre ?? '' }}</td>
                            <!-- Presentacion -->
                            <td>{{ $Producto->presentacione->nombre ?? '' }}</td>
                            <!-- Subcategorias / Categorias -->
                            <td>
                                {{ $Producto->subcategoria->nombre ?? '' }}<br>
                                <span class="opacity-50">
                                    {{ $Producto->subcategoria->categoria->nombre ?? '' }}
                                </span>
                            </td>
                            <!-- Codigo de barras -->
                            <td>{{ $Producto->codigo_barras }}</td>
                            <!-- Descripción -->
                            <td>{{ $Producto->descripcion }}</td>
                            <!-- Precio Ventas -->
                            <td>${{ number_format($Producto->precio_venta, 2) }}</td>
                            <!-- Costo -->
                            <td>${{ number_format($Producto->costo, 2) }}</td>
                            <!-- Imagen -->
                            <td>
                                @if($Producto->imagen)
                                    <a onclick="ImagenModal({{ json_encode($Producto) }})" href="#/">
                                        <img height="150" width="150"
                                             src="{{ Storage::url('public/productos/' . $Producto->imagen) }}"
                                             alt="{{ $Producto->imagen }}"
                                        >
                                    </a>
                                @endif
                            </td>
                            <!-- Estado -->
                            <td class="text-center">
                                @if($Producto->estado == 1)
                                    <span class="fw-bolder rounded p-1 bg-success text-white">ACTIVO</span>
                                @else
                                    <span class="fw-bolder rounded p-1 bg-danger text-white">ELIMINADO</span>
                                @endif
                            </td>
                            <!--BOTONES ACCION-->
                            <x-action-buttons routeName="producto" :params="$Producto" restaurar="true">
                            </x-action-buttons>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <!-- Modal Imagen -->
                <div class="modal fade" id="ModalImagen" tabindex="-1"
                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-10" id="NombreImagen"></h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>&nbsp;
                            </div>
                            <div class="modal-body table-responsive" id="TableImagenModal">
                                <img id="ImagenGrande"
                                     class="img-fluid .img-thumbnail border border-4 rounded"
                                     src="">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar
                                </button>&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('js')
    <script>
        $(document).ready(function () {

        });

        const ImagenModal = (data) => {
            console.log(data);
            $('#NombreImagen').html(data.nombre);
            $('#ImagenGrande').attr('src', data.url);
            $('#ModalImagen').modal('show');
        }

    </script>
@endpush
