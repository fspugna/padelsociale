@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <table class="table">
            <tr>
                <td class="text-left">
                    <h1 style="display: inline-block">Associazioni Banners</h1>
                    <p style="color: red">Assicurarsi di non avere un AdBlock attivo altrimenti questa pagina potrebbe  non funzionare</p>
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
                    
                    <div class="form-group">
                        <label for="id_position">Posizione</label>
                        <select class="form-control" name="id_position" id="id_position">
                            <option value="">Seleziona...</option>
                            @foreach($positions as $position)
                            <option value="{{ $position->id }}">{{ $position->position_name }}</option>
                            @endforeach
                        </select>
                    </div>
                        
                    <div id="div_positionings"></div>
                                            
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
    $("#id_position").on('change', function(){

        var id_position = $(this).val();
        $("#div_positionings").html('');

        $.ajax({
            url: '/admin/banners/position/'+id_position+'/positionings',
            data: {
                "_token": "{{ csrf_token() }}"
            },
            type: 'POST',
            success: function(data){                
                $("#div_positionings").html(data);

                $('input[type="checkbox"]').on('click', function(){
                    var id_banner = $(this).val();
                    
                    if( $(this).is(':checked') === true ){
                        var action = 'add';
                    }else{
                        var action = 'remove';
                    }

                    $.ajax({
                        url: '/admin/banners/positioning/'+action,
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id_banner: id_banner,
                            id_position: id_position
                        },
                        method: "POST"                        
                    });
                
                });
            }
        });
    });    
});
</script>
@endsection
