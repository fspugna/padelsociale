<table class="table table-responsive" id="teams-table">
    <thead>
        <tr>            
            <th>Name</th>            
            <th colspan="3">{!! trans('labels.action') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($teams as $team)
        <tr>            
            <td>{!! $team->team->name !!}</td>            
            <td>             
                {!! Form::open(['route' => ['admin.teams.deleteMyTeam', $team->team->id], 'method' => 'delete']) !!}   
                <div class='btn-group'>
                    <a href="{!! route('admin.teams.show', [$team->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('admin.teams.edit', [$team->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>