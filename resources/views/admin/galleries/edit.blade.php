@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Gallery
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($gallery, ['route' => ['galleries.update', $gallery->id], 'method' => 'patch']) !!}

                        @include('admin.galleries.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection