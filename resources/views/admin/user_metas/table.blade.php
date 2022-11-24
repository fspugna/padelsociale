<table class="table table-responsive" id="userMetas-table">
    <thead>
        <tr>
            <th>Id User</th>
        <th>Meta</th>
        <th>Meta Value</th>
            <th colspan="3">{!! trans('labels.action') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($userMetas as $userMeta)
        <tr>
            <td>{!! $userMeta->id_user !!}</td>
            <td>{!! $userMeta->meta !!}</td>
            <td>{!! $userMeta->meta_value !!}</td>
            <td>
                {!! Form::open(['route' => ['userMetas.destroy', $userMeta->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('userMetas.show', [$userMeta->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('userMetas.edit', [$userMeta->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>