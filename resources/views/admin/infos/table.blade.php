<div class="table-responsive">
    <table class="table" id="infos-table">
        <thead>
            <tr>
                <th>Titolo</th>            
                <th>Stato</th>
                <th colspan="3">Azioni</th>
            </tr>
        </thead>
        <tbody>
        @foreach($infos as $info)
            <tr>
                <td>{!! $info->title !!}</td>            
                <td>
                @if( ($info->status == 0) )
                    <label class="label bg-blue">Bozza</label>
                @elseif( ($info->status == 1) )
                    <label class="label bg-green">Pubblicato</label>
                @endif
                </td>
                <td>
                    {!! Form::open(['route' => ['admin.infos.destroy', $info->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>                   
                        <a href="{!! route('admin.infos.edit', [$info->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
