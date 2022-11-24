@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Club
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($club, ['route' => ['admin.clubsupdate', $club->id], 'method' => 'patch']) !!}

                        @include('admin.clubs.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection