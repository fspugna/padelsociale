<table class="table table-responsive" id="categoryTypes-table">
    <thead>
        <tr>
            <th>Nome</th>
            <th colspan="3">Azioni</th>
        </tr>
    </thead>
    <tbody>
    @foreach($categoryTypes as $categoryType)
        <tr>
            <td width="90%">{!! $categoryType->name !!}</td>
            <td width="10%">
                {!! Form::open(['route' => ['admin.categoryTypes.destroy', $categoryType->id], 'method' => 'delete']) !!}
                <div class='btn-group'>                    
                    <a href="{!! route('admin.categoryTypes.edit', [$categoryType->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>