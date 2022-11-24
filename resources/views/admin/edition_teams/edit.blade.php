@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Edition Team
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($editionTeam, ['route' => ['editionTeams.update', $editionTeam->id], 'method' => 'patch']) !!}

                        @include('admin.edition_teams.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection