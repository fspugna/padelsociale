@extends('admin.layouts.app')



@section('content')
    <section class="content-header">
        <h1 class="pull-left">Multimedia</h1>        
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            @if( $flag_delete )
                <form action="{!! route('admin.images.destroy') !!}" method="post">
                @csrf()
                <input type="hidden" name="id_match" value="{!! $id_match !!}">
            @endif

            <div class="box-body">                

                @include('admin.images.table')                

            </div>
        </div>

        @if( $flag_delete )
                <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Elimina immagini selezionate</button>
                <a href="/admin/images/match/{!! $id_match !!}" class="btn btn-default">Annulla</a>
            </form>
        @endif

        <div class="text-center">
        
        </div>
    </div>
@endsection