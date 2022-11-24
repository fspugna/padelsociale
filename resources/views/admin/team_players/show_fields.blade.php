<!-- Id Team Field -->
<div class="form-group">
    {!! Form::label('id_team', 'Id Team:') !!}
    <p>{!! $teamPlayer->id_team !!}</p>
</div>

<!-- Id Player Field -->
<div class="form-group">
    {!! Form::label('id_player', 'Id Player:') !!}
    <p>{!! $teamPlayer->id_player !!}</p>
</div>

<!-- Starter Field -->
<div class="form-group">
    {!! Form::label('starter', 'Starter:') !!}
    <p>{!! $teamPlayer->starter !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $teamPlayer->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $teamPlayer->updated_at !!}</p>
</div>

