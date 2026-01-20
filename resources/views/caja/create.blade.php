@extends('template')

@section('title','Crear Caja')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Crear Nuevo Caja</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{route('caja.index')}}">Caja</a></li>
            <li class="breadcrumb-item active">Crear Caja</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{route('caja.store')}}" method="post" autocomplete="off">
                @csrf
                <div class="row g-3">
                    <!-- Cantidad Inicial -->
                    <x-form-element id="cantidad_inicial" required="true" focused="true"></x-form-element>

                    <!-- Botones -->
                    <div class="col-md-12 mb-2 text-center">
                        <button type="submit" class="btn btn-success" name="accion" value="continuar">
                            <i class="fa fa-save" aria-hidden="true"></i>
                            Guardar y continuar
                            <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        </button>
                        <a class="btn btn-danger" href="{{ route('caja.index') }}">Cancelar</a>
                    </div>


                </div>
            </form>
        </div>
    </div>

@endsection

@push('js')

@endpush
