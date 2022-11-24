<table class="table table-responsive" id="news-table">
    <thead>
        <tr>
            <th>Title</th>            
            <th>Data</th>            
            <th>Ora</th>            
            <th>Categoria</th>            
            <th>{!! trans('labels.status') !!}</th>
            <th colspan="3">{!! trans('labels.action') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($news as $news)
        <tr>
            <td>{!! $news->title !!}</td>
            <td>{!! $news->excerpt !!}</td>
            <td>{!! $news->time !!}</td>
            <td>{!! $news->category->name !!}</td>
            <td>
            @if( ($news->status == 0) )
                <label class="label bg-blue">Bozza</label>
            @elseif( ($news->status == 1) )
                <label class="label bg-green">Pubblicato</label>
            @endif 
            </td>
            <td>
                {!! Form::open(['route' => ['admin.news.destroy', $news->id], 'method' => 'delete']) !!}
                <div class='btn-group'>                    
                    <a href="{!! route('admin.news.edit', [$news->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>