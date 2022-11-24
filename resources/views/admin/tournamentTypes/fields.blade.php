<!-- Tournament Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tournament_type', 'Tournament Type:') !!}
    {!! Form::text('tournament_type', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.tournamentTypes.index') !!}" class="btn btn-default">{!! trans('labels.cancel'); !!}</a>
</div>
