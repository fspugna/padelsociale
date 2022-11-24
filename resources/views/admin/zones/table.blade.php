<table class="table table-responsive" id="zones-table">
    <thead>
        <tr>
            <th>Città</th>
            <th>Nome zona</th>
            <th>Circoli</th>
            <th>Tornei</th>
            <th colspan="3">{!! trans('labels.action') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($zones as $zone)
        <tr>
            <td>@if( $zone->city ) {!! $zone->city !!} @endif</td>
            <td>{!! $zone->name !!}</td>
            <td>
            @foreach( $zone->clubs as $zoneClub )
                {!! $zoneClub->club->name !!}<br>
            @endforeach
            </td>
            <td>
            @foreach( \App\Models\EditionZone::where('id_zone', '=', $zone->id)->get() as $editionZone )
                {!! $editionZone->edition->edition_name !!}<br>
            @endforeach
            </td>
            <td>
                {!! Form::open(['route' => ['admin.zones.destroy', $zone->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('admin.zones.show', [$zone->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('admin.zones.edit', [$zone->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
