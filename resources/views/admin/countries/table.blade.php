<table class="table table-responsive" id="countries-table">
    <thead>
        <tr>
            <th>Name</th>
            <th colspan="3">{!! trans('labels.action') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($countries as $country)
        <tr>
            <td>{!! $country->name !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.countries.destroy', $country->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('admin.countries.show', [$country->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('admin.countries.edit', [$country->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>