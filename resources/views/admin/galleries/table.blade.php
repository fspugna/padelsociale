<table class="table table-responsive" id="galleries-table">
    <thead>
        <tr>
            <th>Name</th>
        <th>Status</th>
            <th colspan="3">{!! trans('labels.action') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($galleries as $gallery)
        <tr>
            <td>{!! $gallery->name !!}</td>
            <td>{!! $gallery->status !!}</td>
            <td>
                {!! Form::open(['route' => ['galleries.destroy', $gallery->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('galleries.show', [$gallery->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('galleries.edit', [$gallery->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>