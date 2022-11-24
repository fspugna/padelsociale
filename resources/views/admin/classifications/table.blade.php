<table class="table table-responsive" id="classifications-table">
    <thead>
        <tr>
            <th>Id Team</th>
        <th>Points</th>
        <th>Played</th>
        <th>Won</th>
        <th>Set Won</th>
        <th>Set Lost</th>
        <th>Games Won</th>
        <th>Games Lost</th>
            <th colspan="3">{!! trans('labels.action') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($classifications as $classification)
        <tr>
            <td>{!! $classification->id_team !!}</td>
            <td>{!! $classification->points !!}</td>
            <td>{!! $classification->played !!}</td>
            <td>{!! $classification->won !!}</td>
            <td>{!! $classification->set_won !!}</td>
            <td>{!! $classification->set_lost !!}</td>
            <td>{!! $classification->games_won !!}</td>
            <td>{!! $classification->games_lost !!}</td>
            <td>
                {!! Form::open(['route' => ['classifications.destroy', $classification->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('classifications.show', [$classification->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('classifications.edit', [$classification->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>