<table class="table table-responsive" id="clubs-table">
    <thead>
        <tr>
            <th>Città</th>            
            <th>Zona</th>            
            <th>Name</th>
            <th>Address</th>        
            <th>Mobile Phone</th>
            <th colspan="3">{!! trans('labels.action') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($clubs as $club)
        <tr>
            <td>{!! $club->zone->city->name !!}</td>
            <td>{!! $club->zone->name !!}</td>            
            <td>{!! $club->name !!}</td>
            <td>{!! $club->address !!}</td>            
            <td>{!! $club->mobile_phone !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.clubs.destroy', $club->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('admin.clubs.show', [$club->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('admin.clubs.edit', [$club->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>