<!-- Id Category Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_category', 'Id Category:') !!}
    {!! Form::number('id_category', null, ['class' => 'form-control']) !!}
</div>

<!-- Id Team Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_team', 'Id Team:') !!}
    {!! Form::number('id_team', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('categoryTeams.index') !!}" class="btn btn-default">{!! trans('labels.cancel'); !!}</a>
</div>
