<!-- Id Country Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_country', 'Nazione:') !!}
    {!! Form::select('id_country', $countries, null, ['class' => 'form-control']) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.cities.index') !!}" class="btn btn-default">{!! trans('labels.cancel'); !!}</a>
</div>
