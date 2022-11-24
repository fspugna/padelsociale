<!-- Id Team Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_team', 'Id Team:') !!}
    {!! Form::number('id_team', null, ['class' => 'form-control']) !!}
</div>

<!-- Id Tournament Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_tournament', 'Id Tournament:') !!}
    {!! Form::number('id_tournament', null, ['class' => 'form-control']) !!}
</div>

<!-- Id Zone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_zone', 'Id Zone:') !!}
    {!! Form::number('id_zone', null, ['class' => 'form-control']) !!}
</div>

<!-- Id Category Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_category_type', 'Id Category Type:') !!}
    {!! Form::number('id_category_type', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('subscriptions.index') !!}" class="btn btn-default">{!! trans('labels.cancel'); !!}</a>
</div>
