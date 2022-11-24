<table class="table table-responsive" id="groupTeams-table">
    <thead>
        <tr>
            <th>Id Group</th>
        <th>Id Team</th>
            <th colspan="3">{!! trans('labels.action') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($groupTeams as $groupTeam)
        <tr>
            <td>{!! $groupTeam->id_group !!}</td>
            <td>{!! $groupTeam->id_team !!}</td>
            <td>
                {!! Form::open(['route' => ['groupTeams.destroy', $groupTeam->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('groupTeams.show', [$groupTeam->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('groupTeams.edit', [$groupTeam->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>