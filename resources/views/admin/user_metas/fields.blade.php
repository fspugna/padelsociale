<!-- Id User Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_user', 'Id User:') !!}
    {!! Form::number('id_user', null, ['class' => 'form-control']) !!}
</div>

<!-- Meta Field -->
<div class="form-group col-sm-6">
    {!! Form::label('meta', 'Meta:') !!}
    {!! Form::text('meta', null, ['class' => 'form-control']) !!}
</div>

<!-- Meta Value Field -->
<div class="form-group col-sm-6">
    {!! Form::label('meta_value', 'Meta Value:') !!}
    {!! Form::text('meta_value', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('userMetas.index') !!}" class="btn btn-default">{!! trans('labels.cancel'); !!}</a>
</div>
