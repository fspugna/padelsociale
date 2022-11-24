@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Gallery Image
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($galleryImage, ['route' => ['galleryImages.update', $galleryImage->id], 'method' => 'patch']) !!}

                        @include('admin.gallery_images.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection