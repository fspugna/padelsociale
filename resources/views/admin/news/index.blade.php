@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">News</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('admin.news.create') !!}">Nuova news</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('admin.news.table')
            </div>
        </div>
        <div class="text-center">
            {{ $news->links() }}
        </div>
    </div>
@endsection

