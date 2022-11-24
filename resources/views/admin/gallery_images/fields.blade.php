<!-- Id Image Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_image', 'Id Image:') !!}
    {!! Form::number('id_image', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('galleryImages.index') !!}" class="btn btn-default">{!! trans('labels.cancel'); !!}</a>
</div>
