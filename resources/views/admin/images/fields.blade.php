<!-- Path Field -->
<div class="form-group col-sm-6">
    {!! Form::label('path', 'Path:') !!}
    {!! Form::text('path', null, ['class' => 'form-control']) !!}
</div>

<!-- Label Field -->
<div class="form-group col-sm-6">
    {!! Form::label('label', 'Label:') !!}
    {!! Form::text('label', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.images.index') !!}" class="btn btn-default">{!! trans('labels.cancel'); !!}</a>
</div>
