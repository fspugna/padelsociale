<table class="table table-responsive" id="scores-table">
    <thead>
        <tr>
            <th>Id Team</th>
        <th>Set</th>
        <th>Points</th>
            <th colspan="3">{!! trans('labels.action') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($scores as $score)
        <tr>
            <td>{!! $score->id_team !!}</td>
            <td>{!! $score->set !!}</td>
            <td>{!! $score->points !!}</td>
            <td>
                {!! Form::open(['route' => ['scores.destroy', $score->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('scores.show', [$score->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('scores.edit', [$score->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>