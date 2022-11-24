@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Category Type
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($categoryType, ['route' => ['admin.categoryTypes.update', $categoryType->id], 'method' => 'patch']) !!}

                        @include('admin.categoryTypes.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection