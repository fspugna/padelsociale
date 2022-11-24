@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            User Meta
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($userMeta, ['route' => ['userMetas.update', $userMeta->id], 'method' => 'patch']) !!}

                        @include('admin.user_metas.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection