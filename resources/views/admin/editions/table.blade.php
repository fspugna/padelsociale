<table class="table table-responsive" id="editions-table">
    <thead>
        <tr>
            <th>Nome edizione</th>
            <th>Descrizione</th>            
            
            <th colspan="3">{!! trans('labels.action') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($editions as $edition)
        <tr>
            <td>{!! $edition->edition_name !!}</td>
            <td>{!! $edition->edition_description !!}</td>            
            
            <td>
                {!! Form::open(['route' => ['admin.editions.destroy', $edition->id], 'method' => 'delete']) !!}
                <div class='btn-group'>                    
                    <a href="{!! route('admin.editions.edit', [$edition->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>