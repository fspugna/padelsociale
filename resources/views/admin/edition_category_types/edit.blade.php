@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Edition Category Type
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($editionCategoryType, ['route' => ['editionCategoryTypes.update', $editionCategoryType->id], 'method' => 'patch']) !!}

                        @include('edition_category_types.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection