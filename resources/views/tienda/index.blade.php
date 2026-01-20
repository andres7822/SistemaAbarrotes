@extends('template')

@section('title','Tiendas')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Tiendas</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item active">Tiendas</li>
        </ol>

        <x-action-buttons-head routeName="tienda"></x-action-buttons-head>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla Tiendas
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>Nombre</th>
                        <th>Domicilio</th>
                        <th>Descripcion</th>
                        <th>Imagen</th>
                        <th>Encabezado Ticket</th>
                        <th>Pie Ticket</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($Tiendas as $index => $Tienda)
                            <?php
                            $Tiendas[$index]['url'] = Storage::url('public/tiendas/' . $Tienda->imagen);
                            ?>
                        <tr>
                            <!-- id -->
                            <td>{{ $Tienda->id }}</td>
                            <!-- Nombre -->
                            <td>{{ $Tienda->nombre }}</td>
                            <!-- Domicilio -->
                            <td>{{ $Tienda->domicilio ?? '' }}</td>
                            <!-- Descripción -->
                            <td>{{ $Tienda->descripcion ?? '' }}</td>
                            <!-- Imagen -->
                            <td>
                                @if($Tienda->imagen)
                                    <a onclick="ImagenModal({{ json_encode($Tienda) }})" href="#/">
                                        <img height="150" width="150"
                                             src="{{ Storage::url('public/tiendas/' . $Tienda->imagen) }}"
                                             alt="{{ $Tienda->imagen }}"
                                        >
                                    </a>
                                @endif
                            </td>
                            <!-- Encabezado Ticket -->
                            <td>{{ $Tienda->encabezado_ticket ?? '' }}</td>
                            <!-- Pie Ticket -->
                            <td>{{ $Tienda->pie_ticket ?? '' }}</td>
                            <!-- Estado -->
                            <td class="text-center">
                                @if($Tienda->estado == 1)
                                    <span class="fw-bolder rounded p-1 bg-success text-white">ACTIVO</span>
                                @else
                                    <span class="fw-bolder rounded p-1 bg-danger text-white">ELIMINADO</span>
                                @endif
                            </td>
                            <!--BOTONES ACCION-->
                            <x-action-buttons routeName="tienda" :params="$Tienda" restaurar="true">
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
