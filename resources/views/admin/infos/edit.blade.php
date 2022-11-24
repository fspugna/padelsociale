@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Info
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($info, ['route' => ['admin.infos.update', $info->id], 'method' => 'patch', 'files' => true]) !!}

                        @include('admin.infos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection