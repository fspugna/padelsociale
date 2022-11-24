<table class="table table-responsive" id="pages-table">
    <thead>
        <tr>
            <th>Titolo</th>        
            <th>Permalink</th>        
            <th>Stato</th>
            <th colspan="3">{!! trans('labels.action') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($pages as $page)
        <tr>
            <td>{!! $page->title !!}</td>            
            <td>{!! $page->permalink !!}</td>            
            <td>
            @if( ($page->status == 0) )
                <label class="label bg-blue">Bozza</label>
            @elseif( ($page->status == 1) )
                <label class="label bg-green">Pubblicato</label>
            @endif 
            </td>
            <td>
                {!! Form::open(['route' => ['admin.pages.destroy', $page->id], 'method' => 'delete']) !!}
                <div class='btn-group'>                    
                    <a href="{!! route('admin.pages.edit', [$page->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>    
</table>

<div class="text-center">
    {{ $pages->links() }}
</div>