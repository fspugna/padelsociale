@extends('admin.layouts.app')

@section('css')
<!-- include summernote css/js -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
@endsection

@section('content')
    <section class="content-header">
        <h1>
            News
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">                    
                    {!! Form::open(['route' => 'admin.news.store', 'files' => true]) !!}

                        @include('admin.news.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>

<script>
$(document).ready(function() {
    $('#content').summernote({height: 300});
});
</script>
@endsection