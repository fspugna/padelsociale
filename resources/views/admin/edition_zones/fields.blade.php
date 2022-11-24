<!-- Id Zone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_zone', 'Id Zone:') !!}
    {!! Form::number('id_zone', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('editionZones.index') !!}" class="btn btn-default">{!! trans('labels.cancel'); !!}</a>
</div>
