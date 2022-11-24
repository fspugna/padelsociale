@extends('admin.layouts.app')

@section('content')

    <section class="content-header">
        <h1 class="pull-left">
        {!! $division->tournament->name !!}  - 
        Gironi                 
        {!! $division->category->name !!}
        {!! $division->categoryType->name !!}        
        {!! $division->zone->city->name !!}
        {!! $division->zone->name !!}     
        </h1>                
    </section>
    
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')        

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.groups.table')
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection

@section('scripts')
<script>
$(document).ready(function(){    
    $("[id^=online-]").on('ifChanged', function(){
        var flag_online = $(this).is(':checked');
        
        var data = {    
            "_token": "{{ csrf_token() }}",        
            id_group : $(this).attr('id').split('-')[1],        
            flag_online: flag_online
        }

        $.ajax({
            url: '/admin/groups/online',
            type:'post',
            data: data,
            success: function(res){  
            }
        });
    });
});
</script>
@endsection