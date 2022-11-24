<table class="table table-responsive" id="editionZones-table">
    <thead>
        <tr>
            <th>Id Zone</th>
            <th colspan="3">{!! trans('labels.action') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($editionZones as $editionZone)
        <tr>
            <td>{!! $editionZone->id_zone !!}</td>
            <td>
                {!! Form::open(['route' => ['editionZones.destroy', $editionZone->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('editionZones.show', [$editionZone->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('editionZones.edit', [$editionZone->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>