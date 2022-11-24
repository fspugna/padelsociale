<!-- Id Team Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_team', 'Id Team:') !!}
    {!! Form::number('id_team', null, ['class' => 'form-control']) !!}
</div>

<!-- Id Player Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_player', 'Id Player:') !!}
    {!! Form::number('id_player', null, ['class' => 'form-control']) !!}
</div>

<!-- Starter Field -->
<div class="form-group col-sm-6">
    {!! Form::label('starter', 'Starter:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('starter', 0) !!}
        {!! Form::checkbox('starter', '1', null) !!} 1
    </label>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('teamPlayers.index') !!}" class="btn btn-default">{!! trans('labels.cancel'); !!}</a>
</div>
