<table class="table table-responsive" id="subscriptions-table">
    <thead>
        <tr>
            <th>Id Team</th>
        <th>Id Tournament</th>
        <th>Id Zone</th>
        <th>Id Category Type</th>
            <th colspan="3">{!! trans('labels.action') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($subscriptions as $subscription)
        <tr>
            <td>{!! $subscription->id_team !!}</td>
            <td>{!! $subscription->id_tournament !!}</td>
            <td>{!! $subscription->id_zone !!}</td>
            <td>{!! $subscription->id_category_type !!}</td>
            <td>
                {!! Form::open(['route' => ['subscriptions.destroy', $subscription->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('subscriptions.show', [$subscription->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('subscriptions.edit', [$subscription->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>