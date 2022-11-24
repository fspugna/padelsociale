@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Tournament
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   

                    @include('admin.tournaments.fields')

                    {{-- Form::model($tournament, ['route' => ['admin.tournaments.update', $tournament->id], 'method' => 'patch']) !!}
                    {!! Form::close() --}}
               </div>
           </div>
       </div>
   </div>
@endsection