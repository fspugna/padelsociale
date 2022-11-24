@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Edition Zone
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($editionZone, ['route' => ['editionZones.update', $editionZone->id], 'method' => 'patch']) !!}

                        @include('edition_zones.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection