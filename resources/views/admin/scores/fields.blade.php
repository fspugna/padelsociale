<!-- Id Team Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_team', 'Id Team:') !!}
    {!! Form::number('id_team', null, ['class' => 'form-control']) !!}
</div>

<!-- Set Field -->
<div class="form-group col-sm-6">
    {!! Form::label('set', 'Set:') !!}
    {!! Form::text('set', null, ['class' => 'form-control']) !!}
</div>

<!-- Points Field -->
<div class="form-group col-sm-6">
    {!! Form::label('points', 'Points:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('points', 0) !!}
        {!! Form::checkbox('points', '1', null) !!} 1
    </label>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('scores.index') !!}" class="btn btn-default">{!! trans('labels.cancel'); !!}</a>
</div>
