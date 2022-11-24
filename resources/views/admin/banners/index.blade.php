@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <table class="table">
            <tr>
                <td class="text-left">
                    <h1 style="display: inline-block">Banners</h1>
                </td>
                <td class="text-right">
                    <a href="{{route('admin.banners.create')}}" type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Nuovo Banner
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
                <h2>Filtra i banner</h2>
                <form>
                    <div class="form-group">
                        <label>Partner</label>
                        <select class="form-control" name="id_partner">
                            <option value="all">Tutti</option>
                            @foreach($partners as $partner)
                            <option value="{{ $partner->id }}" @if( $sel_partner == $partner->id) selected @endif>{{ $partner->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-primary">Filtra</button>
                </form>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">                                
                <form id="frm_banners" action="{{route('admin.banners.delete')}}" method="POST">
                    
                    @csrf
                    
                    <table class="table table-striped">
                        <tr>
                            <th>Partner</th>
                            <th>Immagine</th>
                            <th>Action</th>
                            <th>Limitazione Città</th>
                            <th>Posizioni</th>
                            <th>Torneo</th>
                            <th>Azioni</th>
                        </tr>
                        @foreach($banners as $banner)
                        <tr>
                            <td>
                                {{ $banner->partner->name }}
                            </td>
                            <td>
                                <img src="{{ url('/storage/'.$banner->filename) }}" style="width: 200px; height: auto">
                            </td>
                            <td>
                                {{ $banner->action }}
                            </td>
                            <td>                                
                                <ul style="list-style-type: none">
                                 @foreach($banner->cities as $city)
                                     <li>{{ $city->city->name }}</li>
                                 @endforeach
                                 </ul>
                             </td>
                            <td>                                
                               <ul style="list-style-type: none">
                                @foreach($banner->positionings as $position)
                                    @if($position->position)
                                    <li>{{ $position->position->position_name }}</li>
                                    @endif
                                @endforeach
                                </ul>
                            </td>
                            <td>
                                @if( !empty($banner->edition) )                                
                                {{ $banner->edition->edition_name }}
                                @endif
                             </td>
                            <td class="pull-right">
                                <div class="btn-group">
                                    <a class="btn btn-sm btn-default" href="/admin/banners/{{ $banner->id }}/edit">
                                        <i class="fa fa-edit"></i> Modifica
                                    </a>
                                    <button type="submit" class="btn btn-sm btn-danger" name="id_banner" value="{{$banner->id}}">
                                        <i class="fa fa-trash"></i> Elimina
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </form>
            </div>
        </div>
        <div class="text-center">
            {{ $banners->links() }}
        </div>
    </div>   
@endsection

@section('scripts')
<script>
$(document).ready(function(){
    $("#frm_banners").on('submit', function(event){                
        if(!confirm('Eliminare il banner selezionato?')){
            event.preventDefault(); 
        }
    });
       
});
</script>
@endsection

