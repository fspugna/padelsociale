@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Tournament Type
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($tournamentType, ['route' => ['admin.tournamentTypes.update', $tournamentType->id], 'method' => 'patch']) !!}

                        @include('admin.tournamentTypes.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection