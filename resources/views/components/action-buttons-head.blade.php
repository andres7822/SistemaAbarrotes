@if($roleId == 1)
    <div class="mb-4">
        <form action="{{ route($routeName . '.edit', [$routeName => $params]) }}">
            <a href="{{ route($routeName . '.create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Nuevo
            </a>
            <button type="submit" class="btn btn-warning" onclick="checarBox()" name="multi_edit" value="true">
                <i class="fa fa-pencil" aria-hidden="true"></i> Editar
            </button>
            <input type="hidden" name="ids" id="ids">
            {{--<a href="#" class="btn btn-warning">Editar</a>--}}
            @if($pdfFile)
                <a href="{{ route($pdfRoute) }}" class="btn btn-danger" target="_blank">
                    <i class="fa fa-file-pdf" aria-hidden="true"></i> {{ $pdfButtonName }}
                </a>
            @endif
        </form>
    </div>
@else
    @can('crear-' . $routeName)
        <div class="mb-4">
            <a href="{{ route($routeName . '.create') }}" class="btn btn-success">Nuevo</a>
            {{--<a href="#" class="btn btn-warning">Editar</a>--}}
        </div>
    @endcan
@endif
