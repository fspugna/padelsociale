<table class="table table-responsive" id="editionTeams-table">
    <thead>
        <tr>
            <th>Id Team</th>
            <th colspan="3">{!! trans('labels.action') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($editionTeams as $editionTeam)
        <tr>
            <td>{!! $editionTeam->id_team !!}</td>
            <td>
                {!! Form::open(['route' => ['editionTeams.destroy', $editionTeam->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('editionTeams.show', [$editionTeam->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('editionTeams.edit', [$editionTeam->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>