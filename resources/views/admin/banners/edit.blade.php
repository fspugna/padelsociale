@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <table class="table">
            <tr>
                <td class="text-left">
                    <h1 style="display: inline-block">Modifica Banner</h1>
                </td>                
            </tr>
        </table>                
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('adminlte-templates::common.errors')        

        <div class="clearfix"></div>

        <form id="frm_positions" action="{{route('admin.banners.update')}}" method="POST" enctype="multipart/form-data">
                            
            @csrf

            <div class="row">
                <div class="col-lg-6">
                    <div class="box box-primary">
                        <div class="box-body">                                                    
                            <div class="form-group">
                                <label for="file">Seleziona immagine banner</label>
                                <input type="file" id="file" name="file" accept="image/x-png,image/gif,image/jpeg,image/png"  class="form-control">
                                <img src="{{ url('/storage/'.$banner->filename) }}" style="width: 200px; height: auto">
                            </div>                    
        
                            <div class="form-group">
                                <label for="action">Partner</label>
                                <select name="id_partner" id="id_partner" class="form-control" required>
                                    <option value="">Seleziona...</option>
                                    @foreach($partners as $partner)
                                        <option value="{{ $partner->id }}"
                                            @if($banner->id_partner == $partner->id) selected @endif
                                            >{{ $partner->name }}</option>
                                    @endforeach
                                </select>
                            </div>                    
        
                            <div class="form-group">
                                <label for="action">Url da richiamare (lasciare vuoto per richiamare la pagina partner)</label>
                                <input type="text" id="action" name="action" class="form-control" placeholder="https://www.sitopartner.it" value="{{ $banner->action }}">
                            </div>                                      
        
                            <div class="form-group">
                                <label for="id_edition">Torneo</label><br>
                                <small>Limita la visualizzazione di questo banner al seguente torneo</small>
                                <select name="id_edition" id="id_edition" class="form-control">
                                    <option value="">Seleziona...</option>
                                    @foreach($edition as $edition)
                                        <option value="{{ $edition->id }}"
                                            @if($banner->id_edition == $edition->id) selected @endif
                                            >{{ $edition->edition_name }}</option>
                                    @endforeach
                                </select>
                            </div> 
                        </div> 
                    </div> 
                    <div class="box box-primary">
                        <div class="box-body">       
                            <h3>Limita la visualizzazione del banner a queste città</h3>         
                            <br>                                                 
                            <ul style="list-style-type: none; padding-left: 20px; padding-right: 20px;">                               
                                <li style="border-bottom: 1px solid #cec; padding: 10px;">
                                    <input type="checkbox" id="sel_all_cities"> Seleziona tutto
                                </li>
                                @foreach($cities as $city)                                
                                <li style="border-bottom: 1px solid #cec; padding: 10px;">
                                    <input type="checkbox" class="banner_cities" name="banner_cities[]" value="{{ $city->id }}"
                                    @if( $banner->cities->contains('id_city', $city->id) ) checked @endif
                                    > {{ $city->name }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>                
                <div class="col-lg-6">
                    <div class="box box-primary">
                        <div class="box-body">       
                            <h3>Mostra questo banner per le seguenti posizioni</h3>         
                            <br>                                                 
                            <ul style="list-style-type: none; padding-left: 20px; padding-right: 20px;">                               
                                <li style="border-bottom: 1px solid #cec; padding: 10px;">
                                    <input type="checkbox" id="sel_all_positions"> Seleziona tutto
                                </li>
                                @foreach($positions as $position)                                
                                <li style="border-bottom: 1px solid #cec; padding: 10px;">
                                    <input type="checkbox" name="banner_positions[]" class="banner_positions" value="{{ $position->id }}"
                                    @if( $banner->positionings->contains('id_banner_positions', $position->id) ) checked @endif
                                    > {{ $position->position_name }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">                    
                <div class="col-lg-12">
                    <div class="form-group">

                        <input type="hidden" name="id_banner" value="{{ $banner->id }}">

                        <a href="/admin/banners" class="btn btn-lg btn-default">Annulla</a>
                        <button type="submit" class="btn btn-lg btn-success">Salva</button>
                    </div>                
                </div>
            </div>
        </form>
    </div>   
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        $('#sel_all_cities').on('ifChecked', function(event){
            $('input.banner_cities').iCheck('check');
        });
        $('#sel_all_cities').on('ifUnchecked', function(event){
            $('input.banner_cities').iCheck('uncheck');
        });
        $('#sel_all_positions').on('ifChecked', function(event){
            $('input.banner_positions').iCheck('check');
        });
        $('#sel_all_positions').on('ifUnchecked', function(event){
            $('input.banner_positions').iCheck('uncheck');
        });
    });
</script>
@endsection