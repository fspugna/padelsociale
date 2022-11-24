@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <table class="table">
            <tr>
                <td class="text-left">
                    <h1 style="display: inline-block">Partners</h1>
                </td>
                <td class="text-right">
                    <a href="{{route('admin.partners.create')}}" type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Nuovo Partner
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
                <h3 style="margin-top: 0">Ricerca</h3>                
                <form method="GET">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="search_name">Nome Partner</label>
                                <input type="text" id="search_name" name="search_name" class="form-control" value="{{ $search_name }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="search_city">Città</label>
                                <select id="search_city" name="search_city" class="form-control">
                                    <option value="">Seleziona...</option>
                                    @foreach($cities as $city)
                                    <option value="{{ $city->id }}" @if($city->id == $search_city) selected @endif>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary">Cerca Partners</button>
                    <a href="/admin/partners" class="btn btn-light">Pulisci campi</a>
                </form>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-body">                
                <form id="frm_partners" action="{{route('admin.partners.actions')}}" method="POST">
                    
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th>Partner</th>
                                <th>Utente</th>
                                <th>Email</th>
                                <th>Cellulare</th>
                                <th>Città</th>
                                <th>Status</th>
                                <th class="text-right">Azioni</th>
                            </tr>
                            @foreach($partners as $partner)
                            <tr>
                                <td>
                                    {{$partner->partners->first()->name}}
                                </td>
                                <td>
                                    {{$partner->name}} {{$partner->surname}}
                                </td>
                                <td>
                                    {{$partner->email}}
                                </td>
                                <td>
                                    {{$partner->mobile_phone}}
                                </td>
                                <td> 
                                    @php
                                    $citta = null;
                                    $p = $partner->partners->first();
                                    if($p){
                                        if( isset($p->city) && $p->city ){
                                            $citta = $p->city->name;
                                        }
                                    }
                                    @endphp                               
                                    {!! $citta !!}
                                </td>
                                <td>
                                    @if( $partner->status == 0 ) <label class="label label-danger">Disabilitato</label>
                                    @elseif( $partner->status == 1 ) <label class="label label-success">Attivo</label>
                                    @endif                                
                                </td>
                                <td class="pull-right">
                                    
                                    <input type="hidden" name="id_partner" value="{{$partner->id}}"
                                        
                                    <div class="btn-group">                                    
                                        <a href="/admin/partners/{{ $partner->id }}/edit" class="btn btn-sm btn-default">
                                            <i class="fa fa-edit"></i> Modifica
                                        </a>
                                        <button type="submit" class="btn btn-sm btn-danger" name="action" value="elimina">
                                            <i class="fa fa-trash"></i> Elimina
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </form>
            </div>
        </div>
        <div class="text-center">
            {{ $partners->links() }}
        </div>
    </div>   
@endsection

@section('scripts')
<script>
$(document).ready(function(){
    $("#frm_partners").on('submit', function(event){
        
        var val = $("[type=submit][clicked=true]").val();
               
        if( val == 'elimina' ){
            if(!confirm('Eliminare il partner selezionato?')){
                event.preventDefault(); 
            }
        }
    });       
    
    $("#frm_partners [type=submit]").click(function() {
        $("[type=submit]", $(this).parents("form")).removeAttr("clicked");
        $(this).attr("clicked", "true");
    });
});
</script>
@endsection

