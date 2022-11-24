<table class="table table-responsive" id="events-table">
    <thead>
        <tr>
            <th>Nome evento</th>
            <th colspan="3">{!! trans('labels.action') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($events as $event)
        <tr>
            <td>{!! $event->name !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.events.destroy', $event->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('admin.events.edit', [$event->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
