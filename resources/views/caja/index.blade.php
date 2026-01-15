@php use Carbon\Carbon; @endphp
@extends('template')

@section('title','Cajas')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Cajas</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item active">Cajas</li>
        </ol>

        @if(!$CajaAbierta)
            @if(auth()->user()->roles->first()->id == 1)
                <div class="mb-4">
                    <a href="{{route('caja.create')}}" class="btn btn-success">Nuevo</a>
                    {{--<a href="#" class="btn btn-warning">Editar</a>--}}
                </div>
            @else
                @can('crear-caja')
                    <div class="mb-4">
                        <a href="{{route('caja.create')}}" class="btn btn-success">Nuevo</a>
                        {{--<a href="#" class="btn btn-warning">Editar</a>--}}
                    </div>
                @endcan
            @endif
        @endif

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla Cajas
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>Fecha Inicio</th>
                        <th>Cantidad Inicial</th>
                        <th>Cantidad Cierre (usuario)</th>
                        <th>Cantidad Cierre Efectivo (sistema)</th>
                        <th>Cambio Dejado</th>
                        <th>Fecha Cierre</th>
                        <th>Observaciones</th>
                        <th>Usuario</th>
                        <th>Tienda</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($Cajas as $Caja)
                        <tr>
                            <!-- id -->
                            <td>{{ $Caja->id }}</td>
                            <!-- Fecha Inicio -->
                            <td>
                                {{ Carbon::parse($Caja->fecha_inicio)->format('d-m-Y H:i:s') }}
                            </td>
                            <!-- Cantidad Inicial -->
                            <td data-order="{{ $Caja->cantidad_inicial }}">
                                ${{ number_format($Caja->cantidad_inicial, 2) }}
                            </td>
                            <!-- Cantidad Cierre -->
                            <td data-order="{{ $Caja->cantidad_cierre }}">
                                ${{ number_format($Caja->cantidad_cierre, 2) }}
                            </td>
                            <!-- Cantidad Cierre Efectivo (sistema) -->
                            <td data-order="{{ $Caja->corte_efectivo_sistema }}">
                                ${{ number_format($Caja->corte_efectivo_sistema, 2) }}
                            </td>
                            <!-- Cambio Dejado -->
                            <td data-order="{{ $Caja->cambio_dejado }}">
                                ${{ number_format($Caja->cambio_dejado, 2) }}
                            </td>
                            <!-- Fecha Cierre -->
                            <td>
                                @if($Caja->fecha_cierre != null)
                                    {{ Carbon::parse($Caja->fecha_cierre)->format('d-m-Y H:i:s') }}
                                @endif
                            </td>
                            <!-- Observaciones -->
                            <td>{{ $Caja->observaciones ?? '' }}</td>
                            <!-- Usuario -->
                            <td>{{ $Caja->user->name }}</td>
                            <!-- tienda -->
                            <td>{{ $Caja->tienda->nombre ?? '' }}</td>
                            <!--BOTONES ACCION-->
                            <td>
                                @if($Caja->fecha_cierre)
                                    <form action="{{ route('caja.edit', ['caja' => $Caja]) }}" method="get">
                                        <button class="btn btn-warning">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                            data-bs-target="#corteModal{{ $Caja->id }}">
                                        Corte
                                    </button>

                                    <!-- corteModal -->
                                    <div class="modal fade" id="corteModal{{ $Caja->id }}" tabindex="-1"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Cerrar
                                                        Modal</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    ¿Estás seguro de proceder al corte?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger"
                                                            data-bs-dismiss="modal">Cancelar
                                                    </button>
                                                    <form action="{{ route('caja.edit', ['caja' => $Caja]) }}"
                                                          method="get">
                                                        <button type="submit" class="btn btn-success">Aceptar
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
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
