@if($roleId == 1)
    <div class="mb-4">
        <a href="{{ route($routeName . '.create') }}" class="btn btn-success">Nuevo</a>
        {{--<a href="#" class="btn btn-warning">Editar</a>--}}
        @if($pdfFile)
            <a href="{{ route($pdfRoute) }}" class="btn btn-danger" target="_blank">
                <i class="fa fa-file-pdf" aria-hidden="true"></i> {{ $pdfButtonName }}
            </a>
        @endif
    </div>
@else
    @can('crear-' . $routeName)
        <div class="mb-4">
            <a href="{{ route($routeName . '.create') }}" class="btn btn-success">Nuevo</a>
            {{--<a href="#" class="btn btn-warning">Editar</a>--}}
        </div>
    @endcan
@endif
