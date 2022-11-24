<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $userMeta->id !!}</p>
</div>

<!-- Id User Field -->
<div class="form-group">
    {!! Form::label('id_user', 'Id User:') !!}
    <p>{!! $userMeta->id_user !!}</p>
</div>

<!-- Meta Field -->
<div class="form-group">
    {!! Form::label('meta', 'Meta:') !!}
    <p>{!! $userMeta->meta !!}</p>
</div>

<!-- Meta Value Field -->
<div class="form-group">
    {!! Form::label('meta_value', 'Meta Value:') !!}
    <p>{!! $userMeta->meta_value !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $userMeta->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $userMeta->updated_at !!}</p>
</div>

