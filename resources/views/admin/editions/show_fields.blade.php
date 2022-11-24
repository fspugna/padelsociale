<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $edition->id !!}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{!! $edition->edition_name !!}</p>
</div>

<!-- Description Field -->
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
    <p>{!! $edition->edition_description !!}</p>
</div>

<!-- Date Start Field -->
<div class="form-group">
    {!! Form::label('date_start', 'Date Start:') !!}
    <p>{!! $edition->date_start !!}</p>
</div>

<!-- Date End Field -->
<div class="form-group">
    {!! Form::label('date_end', 'Date End:') !!}
    <p>{!! $edition->date_end !!}</p>
</div>

<!-- Registration Deadline Date Field -->
<div class="form-group">
    {!! Form::label('registration_deadline_date', 'Registration Deadline Date:') !!}
    <p>{!! $edition->registration_deadline_date !!}</p>
</div>

<!-- Id Image Logo Field -->
<div class="form-group">
    {!! Form::label('id_image_logo', 'Id Image Logo:') !!}
    <p>{!! $edition->id_image_logo !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $edition->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $edition->updated_at !!}</p>
</div>

<!-- Deleted At Field -->
<div class="form-group">
    {!! Form::label('deleted_at', 'Deleted At:') !!}
    <p>{!! $edition->deleted_at !!}</p>
</div>

