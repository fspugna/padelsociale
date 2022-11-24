<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $news->id !!}</p>
</div>

<!-- Title Field -->
<div class="form-group">
    {!! Form::label('title', 'Title:') !!}
    <p>{!! $news->title !!}</p>
</div>

<!-- Excerpt Field -->
<div class="form-group">
    {!! Form::label('excerpt', 'Excerpt:') !!}
    <p>{!! $news->excerpt !!}</p>
</div>

<!-- Content Field -->
<div class="form-group">
    {!! Form::label('content', 'Content:') !!}
    <p>{!! $news->content !!}</p>
</div>

<!-- Id Image Field -->
<div class="form-group">
    {!! Form::label('id_image', 'Id Image:') !!}
    <p>{!! $news->id_image !!}</p>
</div>

<!-- Like Field -->
<div class="form-group">
    {!! Form::label('like', 'Like:') !!}
    <p>{!! $news->like !!}</p>
</div>

<!-- Status Field -->
<div class="form-group">
    {!! Form::label('status', 'Status:') !!}
    <p>{!! $news->status !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $news->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $news->updated_at !!}</p>
</div>

