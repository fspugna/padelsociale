@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>Evento</h1>
   </section>
   <div class="content">
        @include('adminlte-templates::common.errors')
        {!! Form::model($event, ['route' => ['admin.events.update', $event->id], 'method' => 'patch']) !!}

            @include('admin.events.fields')

        {!! Form::close() !!}
   </div>
@endsection
