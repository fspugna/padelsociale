<table class="table table-responsive" id="editionCategories-table">
    <thead>
        <tr>
            <th>Id Category</th>
            <th colspan="3">{!! trans('labels.action') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($editionCategories as $editionCategory)
        <tr>
            <td>{!! $editionCategory->id_category !!}</td>
            <td>
                {!! Form::open(['route' => ['editionCategories.destroy', $editionCategory->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('editionCategories.show', [$editionCategory->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('editionCategories.edit', [$editionCategory->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>