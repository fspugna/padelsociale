<table class="table table-responsive" id="galleryImages-table">
    <thead>
        <tr>
            <th>Id Image</th>
            <th colspan="3">{!! trans('labels.action') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($galleryImages as $galleryImage)
        <tr>
            <td>{!! $galleryImage->id_image !!}</td>
            <td>
                {!! Form::open(['route' => ['galleryImages.destroy', $galleryImage->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('galleryImages.show', [$galleryImage->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('galleryImages.edit', [$galleryImage->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>