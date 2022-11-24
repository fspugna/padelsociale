<table class="table table-responsive" id="editionCategoryTypes-table">
    <thead>
        <tr>
            <th>Id Category Type</th>
            <th colspan="3">{!! trans('labels.action') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($editionCategoryTypes as $editionCategoryType)
        <tr>
            <td>{!! $editionCategoryType->id_category_type !!}</td>
            <td>
                {!! Form::open(['route' => ['editionCategoryTypes.destroy', $editionCategoryType->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('editionCategoryTypes.show', [$editionCategoryType->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('editionCategoryTypes.edit', [$editionCategoryType->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>