<table class="table table-responsive table-striped" id="categories-table">
    <thead>
        <tr>            
            <th>Nome</th>                        
            <th colspan="3">Azioni</th>
        </tr>
    </thead>
    <tbody>
    @foreach($categories as $category)
        <tr>            
            <td width="90%">{!! $category->name !!}</td>            
            <td width="10%">
                {!! Form::open(['route' => ['admin.categories.destroy', $category->id], 'method' => 'delete']) !!}
                <div class='btn-group'>                    
                    <a href="{!! route('admin.categories.edit', [$category->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>