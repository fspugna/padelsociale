<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $tournament->id !!}</p>
</div>

<!-- Id Edition Field -->
<div class="form-group">
    {!! Form::label('id_edition', 'Id Edition:') !!}
    <p>{!! $tournament->id_edition !!}</p>
</div>

<!-- Id Tournament Type Field -->
<div class="form-group">
    {!! Form::label('id_tournament_type', trans('labels.'.$tournament->id_tournament_type) ) !!}
    <p>{!! $tournament->id_tournament_type !!}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{!! $tournament->name !!}</p>
</div>

<!-- Date Start Field -->
<div class="form-group">
    {!! Form::label('date_start', 'Date Start:') !!}
    <p>{!! $tournament->date_start !!}</p>
</div>

<!-- Date End Field -->
<div class="form-group">
    {!! Form::label('date_end', 'Date End:') !!}
    <p>{!! $tournament->date_end !!}</p>
</div>

<!-- Description Field -->
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
    <p>{!! $tournament->description !!}</p>
</div>

<!-- Deleted At Field -->
<div class="form-group">
    {!! Form::label('deleted_at', 'Deleted At:') !!}
    <p>{!! $tournament->deleted_at !!}</p>
</div>

