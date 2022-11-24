<div class="table-responsive">
    <table class="table" id="newsCategories-table">
        <thead>
            <tr>
                <th>Categoria</th>
                <th>Tot. notizie</th>
                <th colspan="3">Azioni</th>
            </tr>
        </thead>
        <tbody>
        @foreach($newsCategories as $newsCategory)
            <tr>
                <td>{!! $newsCategory->name !!}</td>
                <td>
                    {!! count($newsCategory->news) !!}
                </td>
                <td>
                    {!! Form::open(['route' => ['admin.newsCategories.destroy', $newsCategory->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>                        
                        <a href="{!! route('admin.newsCategories.edit', [$newsCategory->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                        @if( count($newsCategory->news) == 0 )
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                        @endif
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
