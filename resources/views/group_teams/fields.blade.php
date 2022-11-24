<!-- Id Group Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_group', 'Id Group:') !!}
    {!! Form::number('id_group', null, ['class' => 'form-control']) !!}
</div>

<!-- Id Team Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_team', 'Id Team:') !!}
    {!! Form::number('id_team', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('groupTeams.index') !!}" class="btn btn-default">{!! trans('labels.cancel'); !!}</a>
</div>
