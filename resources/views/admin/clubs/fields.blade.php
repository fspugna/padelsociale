<!-- Id Zone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_zone', 'Zona') !!}
    {!! Form::select('id_zone', $zones, null, ['class' => 'form-control']) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-12">
    {!! Form::label('name', 'Nome Circolo') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Address Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address', 'Indirizzo:') !!}
    {!! Form::text('address', null, ['class' => 'form-control']) !!}
</div>

<!-- Mobile Phone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('mobile_phone', 'Cellulare') !!}
    {!! Form::text('mobile_phone', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.clubs.index') !!}" class="btn btn-default">{!! trans('labels.cancel'); !!}</a>
</div>
