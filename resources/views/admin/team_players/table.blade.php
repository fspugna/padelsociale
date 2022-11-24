<table class="table table-responsive" id="teamPlayers-table">
    <thead>
        <tr>
            <th>Id Team</th>
        <th>Id Player</th>
        <th>Starter</th>
            <th colspan="3">{!! trans('labels.action') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($teamPlayers as $teamPlayer)
        <tr>
            <td>{!! $teamPlayer->id_team !!}</td>
            <td>{!! $teamPlayer->id_player !!}</td>
            <td>{!! $teamPlayer->starter !!}</td>
            <td>
                {!! Form::open(['route' => ['teamPlayers.destroyMyTeam', $teamPlayer->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('teamPlayers.show', [$teamPlayer->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('teamPlayers.edit', [$teamPlayer->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>