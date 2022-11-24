<table class="table table-responsive" id="tournamentTypes-table">
    <thead>
        <tr>
            <th>Tournament Type</th>
            <th colspan="3">{!! trans('labels.action') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($tournamentTypes as $tournamentType)
        <tr>
            <td>{!! $tournamentType->tournament_type !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.tournamentTypes.destroy', $tournamentType->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('admin.tournamentTypes.show', [$tournamentType->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('admin.tournamentTypes.edit', [$tournamentType->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>