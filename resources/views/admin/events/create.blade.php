@extends('admin.layouts.app')

@section('css')
<!-- include summernote css/js -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
@endsection


@section('content')
    <section class="content-header">
        <h1>
            Nuovo Evento
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        {!! Form::model($event, ['route' => ['admin.events.store', $event->id], 'method' => 'post']) !!}

            @include('admin.events.fields')

        {!! Form::close() !!}

    </div>
@endsection
