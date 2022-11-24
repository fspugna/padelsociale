<!-- Id Club Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_club', 'Id Club:') !!}
    {!! Form::number('id_club', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('editionClubs.index') !!}" class="btn btn-default">{!! trans('labels.cancel'); !!}</a>
</div>
