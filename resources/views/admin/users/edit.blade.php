@extends('admin.layouts.app')

@section('css')
<!-- include summernote css/js -->
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
@endsection


@section('content')
    <section class="content-header">
        <h1>
            Utente
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               
                {!! Form::model($user, ['route' => ['admin.users.update', $user->id], 'method' => 'patch']) !!}

                    @include('admin.users.fields')

                {!! Form::close() !!}
            
           </div>
       </div>
   </div>
@endsection

@section('scripts')
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
<script>
$(document).ready(function(){       
    console.log($('#club_description').length);
    if( $('#club_description').length ){
        $('#club_description').summernote({height: 300});
    }
});
</script>
@endsection