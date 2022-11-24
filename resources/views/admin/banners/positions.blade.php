@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <table class="table">
            <tr>
                <td class="text-left">
                    <h1 style="display: inline-block">Posizioni Banners</h1>
                </td>
                <td class="text-right">
                    <a href="{{route('admin.banners.positions.create')}}" type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Nuova posizione
                    </a>
                </td>
            </tr>
        </table>                
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('adminlte-templates::common.errors')        

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <form id="frm_positions" action="{{route('admin.banners.positions.delete')}}" method="POST">
                    
                    @csrf
                    
                    <table class="table table-striped">
                        @foreach($positions as $position)
                        <tr>
                            <td>{{$position->position_name}}</td>
                            <td class="pull-right">
                                <button type="submit" class="btn btn-sm btn-danger" name="btn_del_position" value="{{$position->id}}">
                                    <i class="fa fa-trash"></i> Elimina
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </form>
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>   
@endsection

@section('scripts')
<script>
$(document).ready(function(){
    $("#frm_positions").on('submit', function(event){
        if(!confirm('Eliminare la posizione selezionata')){
            event.preventDefault(); 
        }
    });
});
</script>
@endsection
