<!-- Id Team Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_team', 'Id Team:') !!}
    {!! Form::number('id_team', null, ['class' => 'form-control']) !!}
</div>

<!-- Points Field -->
<div class="form-group col-sm-6">
    {!! Form::label('points', 'Points:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('points', 0) !!}
        {!! Form::checkbox('points', '1', null) !!} 1
    </label>
</div>

<!-- Played Field -->
<div class="form-group col-sm-6">
    {!! Form::label('played', 'Played:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('played', 0) !!}
        {!! Form::checkbox('played', '1', null) !!} 1
    </label>
</div>

<!-- Won Field -->
<div class="form-group col-sm-6">
    {!! Form::label('won', 'Won:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('won', 0) !!}
        {!! Form::checkbox('won', '1', null) !!} 1
    </label>
</div>

<!-- Set Won Field -->
<div class="form-group col-sm-6">
    {!! Form::label('set_won', 'Set Won:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('set_won', 0) !!}
        {!! Form::checkbox('set_won', '1', null) !!} 1
    </label>
</div>

<!-- Set Lost Field -->
<div class="form-group col-sm-6">
    {!! Form::label('set_lost', 'Set Lost:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('set_lost', 0) !!}
        {!! Form::checkbox('set_lost', '1', null) !!} 1
    </label>
</div>

<!-- Games Won Field -->
<div class="form-group col-sm-6">
    {!! Form::label('games_won', 'Games Won:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('games_won', 0) !!}
        {!! Form::checkbox('games_won', '1', null) !!} 1
    </label>
</div>

<!-- Games Lost Field -->
<div class="form-group col-sm-6">
    {!! Form::label('games_lost', 'Games Lost:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('games_lost', 0) !!}
        {!! Form::checkbox('games_lost', '1', null) !!} 1
    </label>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('classifications.index') !!}" class="btn btn-default">{!! trans('labels.cancel'); !!}</a>
</div>
