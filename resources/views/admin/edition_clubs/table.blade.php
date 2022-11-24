<table class="table table-responsive" id="editionClubs-table">
    <thead>
        <tr>
            <th>Id Club</th>
            <th colspan="3">{!! trans('labels.action') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($editionClubs as $editionClub)
        <tr>
            <td>{!! $editionClub->id_club !!}</td>
            <td>
                {!! Form::open(['route' => ['editionClubs.destroy', $editionClub->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('editionClubs.show', [$editionClub->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('editionClubs.edit', [$editionClub->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>