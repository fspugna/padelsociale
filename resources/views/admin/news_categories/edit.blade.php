@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            News Category
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($newsCategory, ['route' => ['admin.newsCategories.update', $newsCategory->id], 'method' => 'patch']) !!}

                        @include('admin.news_categories.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection