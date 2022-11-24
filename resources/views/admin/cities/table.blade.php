<table class="table table-responsive" id="cities-table">
    <thead>
        <tr>
            <th>Name</th>       
            <th>Nazione</th>
            <th colspan="3">{!! trans('labels.action') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($cities as $city)
        <tr>
            <td>{!! $city->name !!}</td>            
            <td>{!! $city->country->name !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.cities.destroy', $city->id], 'method' => 'delete']) !!}
                <div class='btn-group'>                    
                    <a href="{!! route('admin.cities.edit', [$city->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>