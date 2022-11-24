<table class="table table-responsive" id="categoryTeams-table">
    <thead>
        <tr>
            <th>Id Category</th>
        <th>Id Team</th>
            <th colspan="3">{!! trans('labels.action') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($categoryTeams as $categoryTeam)
        <tr>
            <td>{!! $categoryTeam->id_category !!}</td>
            <td>{!! $categoryTeam->id_team !!}</td>
            <td>
                {!! Form::open(['route' => ['categoryTeams.destroy', $categoryTeam->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('categoryTeams.show', [$categoryTeam->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('categoryTeams.edit', [$categoryTeam->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>