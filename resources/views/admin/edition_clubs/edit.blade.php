@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Edition Club
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($editionClub, ['route' => ['editionClubs.update', $editionClub->id], 'method' => 'patch']) !!}

                        @include('admin.edition_clubs.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection