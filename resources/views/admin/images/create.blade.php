@extends('admin.layouts.app')

@section('css')
<link rel="stylesheet" href="{!! asset('public/css/dropzone.css') !!}" />
@endsection

@section('content')
    <section class="content-header">
        <h1>
            Carica immagini
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')

        <span>Clicca nello spazio qui sotto o trascina dentro le immmagini</span>
        <div id="my-dropzone" style="height: 300px; width: 100%; padding: 20px; border: 1px solid #ccc; background-color: #fff"></form>                              
        </div>

        @csrf
        <input type="hidden" name="id_user" value="{!! $id_user !!}">
        <input type="hidden" name="id_match" value="{!! $id_match !!}">
        <br>
        <a href="{!! route('admin.images.match.index', ['id_match' => $id_match]) !!}" class="btn btn-primary">Indietro</a>

    </div>

@endsection

@section('scripts')
<script src="{!! asset('public/js/dropzone.js') !!}"></script>
<script>
$(document).ready(function(){

    var token = $('input[name="_token"]').val();
    var id_match = $('input[name="id_match"]').val();
    var id_user = $('input[name="id_user"]').val();    

    var myDropzone = new Dropzone("#my-dropzone", {
        url: "{!! route('admin.images.store') !!}"
    });
    $("#my-dropzone").addClass("dropzone");
    myDropzone.on('sending', function(file, xhr, formData){
        console.log("dropzone sendind", formData);
        formData.append('_token', token);
        formData.append('id_user', id_user);
        formData.append('id_match', id_match);
    });
});
</script>
@endsection
