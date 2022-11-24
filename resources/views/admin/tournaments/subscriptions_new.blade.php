@extends('admin.layouts.app')

@section('css')
<style>
    span.twitter-typeahead{ width: 100% !important; }

    div.tt-menu{ width: 100% !important; }
</style>
@endsection

@section('content')

    <section class="content-header">
        <div class="row">
            <ol class="breadcrumb">                
                <li><a href="/admin/editions/{!! $tournament->edition->id !!}/edit"><i class="fa fa-dashboard"></i> Edizione</a></li>            
            </ol>            
        </div>
        <div class="row">
            <div class="col-sm-12">
                <h1>Iscrizioni</h1>     
            </div>
        </div>        
    </section> 

    <div class="content">
        
        <input type="hidden" id="edition_type" value="{!! $tournament->edition->edition_type !!}">
        
        <button class="btn btn-success" onClick="addDivision();">Aggiungi categoria</button>

    {!! Form::open(['route' => 'admin.assigncategories']) !!}
    <div class="table-responsive">
        <table class="table table-striped">
        <tr>
            <th>Zona</th>            
            <th>Tipologia</th>
            <th>Categoria</th>
            <th>Iscritti</th>
            <th>Gironi</th>
            <th>Online</th>
            <th>Azioni</th>
        </tr>
        
        @foreach($divisions as $division)                      
            <tr>
                <td>{!! $division->zone->city->name !!} - {!! $division->zone->name !!}</td>                
                <td>{!! $division->categoryType->name !!}</td>
                <td>{!! $division->category->name !!}</td>
                
                @if( $division->tournament->edition->edition_type < 2 )
                    @php
                        $subscriptions  = \App\Models\Subscription::where('id_tournament', $tournament->id)->where('id_zone', '=', $division->id_zone)->where('id_category', '=', $division->id_category)->where('id_category_type', '=', $division->id_category_type)->get();                
                    @endphp
                @elseif( $division->tournament->edition->edition_type == 2 )
                    @php
                        $subscriptions  = \App\Models\MacroSubscription::where('id_tournament', $tournament->id)->where('id_zone', '=', $division->id_zone)->where('id_category', '=', $division->id_category)->where('id_category_type', '=', $division->id_category_type)->get();                
                    @endphp
                @endif
                                
                <td><a href="javascript:void(0);" onClick="openCloseCategories({!! $division->id !!})">{!! count($subscriptions) !!}</a></td>
                <td>{!! count($division->groups) !!}</td>
                <td>{!! count(\App\Models\Group::where('id_division', '=', $division->id)->where('flag_online', '=', 1)->get()) !!}</td>
                <td>
                    @if( count($division->groups) == 0)
                        <a href="javascript:void(0);" class="btn btn-danger btn-sm" onClick="deleteDivision({!! $division->id !!});"><i class="fa fa-trash"></i> Elimina</i>
                    @endif

                    @if( $division->generated == 0 )                    
                    <a href="{!! route('admin.groups.prepare', [ 'id_division' => $division->id ]) !!}" class="btn btn-primary btn-sm"><i class="fa fa-cogs"></i> Crea i gironi</a>
                    @endif

                    @if( $division->generated == 1)
                    <a href="{!! route('admin.divisions.index', ['id_division' => $division->id ]) !!}" class="btn btn-success btn-sm" ><i class="fa fa-list"></i> Gironi</a>                                    
                    @endif
                </td>
            </tr>
            <tr id="container-{!! $division->id !!}" style="display: none">
                <td colspan="7" style="text-align: left; background-color: #ccc">
                    
                        @foreach($subscriptions as $subscription)                                                   
                            <div class="pull-left" style="height: 350px; overflow: auto;">
                            <table class="table subscription-team" data-id-team="{{ $subscription->team->id}}" style="width: 300px; min-height: 180px; margin: 5px">
                                <thead>                                    
                                @if( $tournament->edition->edition_type == 2)
                                <tr>
                                    <th colspan="2" class="text-center"><strong>{!! $subscription->team->club->name !!}</strong></th>
                                </tr>                                    
                                <tr>
                                    <th colspan="2" class="text-center"><strong>{!! $subscription->team->name !!}</strong></th>
                                </tr>                                
                                @endif
                                <tr>
                                    <th colspan="2">
                                        {!! Form::select('subscription['.$subscription->id.']', $arr_categories, $subscription->id_category, ['class' => 'form-control']) !!}                                    
                                    </th>
                                </tr>
                                </thead>
                                <tbody style="max-height: 300px">
                                @foreach($subscription->team->players as $player)    
                                    @if( $player->player )                                
                                    <tr data-id-player="{{ $player->player->id  }}">                                
                                        <td style="width: 45px; vertical-align: middle">
                                            @php
                                            $avatar = false;
                                            @endphp
                                            @foreach($player->player->metas as $meta)                                        
                                                @if($meta->meta == 'avatar')
                                                @php 
                                                $avatar = true;
                                                @endphp
                                                <img src="{!! env('APP_URL') !!}/storage/{!! $meta->meta_value !!}" class="img-circle pull-left" style="width: 40px; height: 40px;">                
                                                @endif
                                            @endforeach
                                            @if( !$avatar )
                                            <img src="https://via.placeholder.com/40?text=?" class="img-circle pull-left">
                                            @endif
                                        </td>
                                        <td style="vertical-align: middle">
                                            {!! $player->player->name !!} {!! $player->player->surname !!}
                                        </td>                                        
                                    </tr>                                      
                                    @endif                                       
                                @endforeach
                                <tr>
                                    <td colspan="2">
                                        <a href="/admin/teams/{{ $subscription->team->id }}/edit" class="btn btn-warning btn-sm btn-block">Modifica</a>
                                    </td>
                                </tr>
                                </tbody>                                                                                 
                            </table>
                            </div>                        
                                
                        @endforeach
                    
                </td>
            </tr>
                
        @endforeach                
       
        @foreach($subscriptions_no_cat as $zone => $categoryTypes)            
            @foreach($categoryTypes as $categoryType => $subscriptions)
                
                <tr class="danger">
                    <td>{!! \App\Models\Zone::where('id', '=', $zone)->first()->city->name !!} - {!! \App\Models\Zone::where('id', '=', $zone)->first()->name !!}</td>                
                    <td>{!! \App\Models\CategoryType::where('id', '=', $categoryType)->first()->name !!}</td>
                    <td>Da assegnare</td>                    
                    <td><a id="counter_subscription_{!! $division->id !!}" href="javascript:void(0);" onClick="openCloseNoCat({!! $division->id !!}, {!! $zone !!}, {!! $categoryType !!})">{!! count($subscriptions) !!}</a></td>
                    <td colspan="3">                        
                        <a href="javascript:void(0);" class="btn btn-warning" onclick="subscribeTeam({!! $tournament->id !!}, {!! $zone !!}, {!! $categoryType !!})"><i class="fa fa-plus"></i> Iscrivi squadra</a>
                    </td>
                </tr>
                <tr id="container-nocat-{!! $division->id !!}-{!! $zone !!}-{!! $categoryType !!}" style="display: none">
                    <td colspan="7" style="text-align: right; background-color: #ccc">
                        
                            @foreach($subscriptions as $subscription)                    
                            <div class="pull-left" style="max-height: 300px; overflow: auto">      
                                <table id="table_subscription_{!! $subscription->id !!}" class="table pull-left" style="width: 300px; min-height: 180px; margin: 5px;">                                                                       
                                    <thead>
                                    @if( $tournament->edition->edition_type == 2 )
                                    <tr>
                                        <th colspan="2" class="text-center"><strong>{!! $subscription->team->club->name !!}</strong></th>
                                    </tr>                                    
                                    <tr>
                                        <th colspan="2" class="text-center"><strong>{!! $subscription->team->name !!}</strong></th>
                                    </tr>                                    
                                    @endif
                                    <tr>
                                        <td colspan="2">
                                            {!! Form::select('subscription['.$subscription->id.']', $arr_categories, $subscription->id_category, ['class' => 'form-control']) !!}                                    
                                        </td>
                                    </tr>
                                    </thead>
                                    
                                    <tbody>
                                    @foreach($subscription->team->players as $player) 
                                        @if( $player->player )                                     
                                            <tr>                                
                                                <td style="width: 45px; vertical-align: middle">
                                                    @php
                                                    $avatar = false;
                                                    @endphp
                                                    @foreach($player->player->metas as $meta)                                            
                                                        @if($meta->meta == 'avatar')
                                                        @php 
                                                        $avatar = true;
                                                        @endphp
                                                        <img src="{!! env('APP_URL') !!}/storage/{!! $meta->meta_value !!}" class="img-circle pull-left" style="width: 40px; height: 40px;">                
                                                        @endif
                                                    @endforeach
                                                    @if( !$avatar )                                        
                                                    <img src="https://via.placeholder.com/40?text=?" class="img-circle pull-left">
                                                    @endif
                                                </td>
                                                <td class="text-left" style="vertical-align: middle">
                                                    {!! $player->player->name !!} {!! $player->player->surname !!}                                                    
                                                </td>                                                                                                                                                                               
                                            </tr>                                          
                                        @endif
                                    @endforeach                                      
                                    <tr>
                                        <td colspan="2">                                                                                        
                                            <button type="button" class="btn btn-block btn-sm btn-danger" onClick="removeSubscription({!! $subscription->id !!}, {!! $division->id !!}, {!! $subscription->tournament->edition->edition_type !!})"><i class="fa fa-trash"></i> Rimuovi</button>
                                        </td>
                                    </tr>      
                                    </tbody>                                         
                                </table>
                            </div>
                            @endforeach
                        
                    </td>
                </tr>
            
            @endforeach                
        @endforeach

        
        </table>
    </div>
    
        <div class="row">
            <div class="col-sm-12">
                {!! Form::hidden('id_tournament', $tournament->id) !!}                
                {!! Form::submit('Salva', ['class' => 'btn btn-primary']) !!}
                <a href="{!! route('admin.editions.edit', ['id_edition' => $tournament->edition->id] ) !!}" class="btn btn-default">{!! trans('labels.cancel'); !!}</a>
            </div>
        </div>
    {!! Form::close() !!}
    </div>

    <div id="modal-add-division" class="modal fade" tabindex="-1" role="dialog">            
        <div class="modal-dialog" role="document">            
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Crea nuova categoria</h4>
                </div>
                <div class="modal-body" style="max-height: 300px; overflow: auto">                                    

                    <div class="row">
                        <div class="col-xs-12">
                            
                            <!-- Name Field -->
                            <table class="table table-striped">
                                <thead>                                
                                    <td>Zona</td>
                                    <td>Tipologia</td>                                
                                    <td>Categoria</td>
                                </thead>
                                <tbody id="tbody-divisions">
                                    @foreach( $tournament->edition->zones as $editionZone )
                                        @foreach( $tournament->edition->categoryTypes as $editionCategoryType )
                                            @foreach( $tournament->edition->categories as $editionCategory ) 
                                                <tr>
                                                    <td>{!! $editionZone->zone->name !!}</td>
                                                    <td>{!! $editionCategoryType->categoryType->name !!}</td>
                                                    <td>{!! $editionCategory->category->name !!}</td>
                                                    <td>
                                                        @if( \App\Models\Division::where('id_tournament', '=', $tournament->id)->where('id_zone', '=', $editionZone->id_zone)->where('id_category_type', '=', $editionCategoryType->id_category_type)->where('id_category', '=', $editionCategory->id_category)->first() )
                                                            Esiste
                                                        @else
                                                            <button id="btn_{!! $tournament->id !!}_{!! $editionZone->id_zone !!}_{!! $editionCategoryType->id_category_type !!}_{!! $editionCategory->id_category !!}" class="btn btn-success" onClick="createDivision({!! $tournament->id !!}, {!! $editionZone->id_zone !!} , {!! $editionCategoryType->id_category_type !!}, {!! $editionCategory->id_category !!});">Crea</button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">                                            
                    <button type="button" class="btn btn-default" onClick="closeModalAddDivision()">Chiudi</button>
                </div>
                
            </div><!-- /.modal-content -->        
        </div><!-- /.modal-dialog -->                
    </div><!-- /.modal -->

    <div id="modal-add-division-team" class="modal fade" tabindex="-1" role="dialog">            
        <div class="modal-dialog" role="document">            
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Torneo</h4>
                </div>
                <div class="modal-body">                    
                <div class="row">
                    <div class="col-xs-12">    
                        
                        <div class="form-group">
                            <label for="team_name">Nome Squadra ( Se vuoto assegnato in automatico )</label>
                            <input type="text" class="form-control" name="team_name" id="team_name">
                        </div>
                        
                        @if( $tournament->edition->edition_type == 2 )
                        <div class="form-group">
                            <label for="id_club">Circolo</label>
                            {!! Form::select('id_club', $clubs, null, ['id' => 'id_club', 'class' => 'form-control']); !!}
                        </div>
                        @endif
                        
                        <form class="typeahead" role="search">
                            <div class="form-group">
                                <input type="search" name="q" id="search-input" class="form-control search-input" placeholder="Aggiungi giocatore" autocomplete="off" style="width: 100%;">
                            </div>
                        </form>                            
                    
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <!-- Name Field -->
                        <table class="table table-striped">
                            <thead>                                
                                <td>Giocatore</td>
                                <td>Email</td>                                
                                <td></td>
                            </thead>
                            <tbody id="tbody-team-players">
                                
                            </tbody>
                        </table>
                    </div>
                </div>

                </div>
                <div class="modal-footer">                        
                    <input type="hidden" id="sel_player" value="">
                    <input type="hidden" name="id_tournament" id="id_tournament" value="">
                    <input type="hidden" name="id_zone" id="id_zone" value="">
                    <input type="hidden" name="id_category_type" id="id_category_type" value="">
                    <button type="button" class="btn btn-primary" onClick="addTeam()">{!! trans('labels.save') !!}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{!! trans('labels.cancel') !!}</button>
                </div>
            </div><!-- /.modal-content -->        
        </div><!-- /.modal-dialog -->                
    </div><!-- /.modal -->

@endsection

@section('scripts')
<script>
function openCloseCategories (id){        
    $('#container-'+id).toggle();
    
}   

function openCloseNoCat (id_division, id_zone, id_category_type){        
    $('#container-nocat-'+id_division+'-'+id_zone+'-'+id_category_type).toggle();
    
}   


var add_players = [];
var add_team_group = null;

$(document).ready(function(){    
   
    // Set the Options for "Bloodhound" suggestion engine
    var engine = new Bloodhound({
        remote: {
            url: '/admin/player/search?q=%QUERY%',
            wildcard: '%QUERY%'
        },
        datumTokenizer: Bloodhound.tokenizers.whitespace('q'),
        queryTokenizer: Bloodhound.tokenizers.whitespace
    });

    $(".search-input").change(function(){
        $("#player_id").val('');
    });

    $(".search-input").typeahead({
        hint: true,
        highlight: true,
        minLength: 3,
        afterSelect: function(item) {
            console.log("afterSelect", item, this.$element);
            this.$element[0].value = item.value
        }
    }, {
        source: engine.ttAdapter(),

        // This will be appended to "tt-dataset-" to form the class name of the suggestion menu.
        name: 'usersList',

        display: function(data){ return data.name + ' ' + data.surname },

        // the key from the array we want to display (name,id,email,etc...)
        templates: {
            
            empty: [
                '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
            ],
            header: [
                '<div class="list-group search-results-dropdown">'
            ],
            suggestion: function (data) {
                return '<a href="javascript:void(0);" onClick="selectPlayer('+data.id+')" class="list-group-item">' + data.name + ' ' + data.surname + ' <br> ' + data.email + '</a>'
            }
        }
    });    
});

function subscribeTeam(id_tournament, id_zone, id_category_type){
    
    add_players = [];
    $("#modal-add-division-team #id_tournament").val(id_tournament);
    $("#modal-add-division-team #id_zone").val(id_zone);
    $("#modal-add-division-team #id_category_type").val(id_category_type);

    $("#modal-add-division-team").modal('show');
}

function addDivision(){
    $("#modal-add-division").modal('show');
}

function closeModalAddDivision(){
    $("#modal-add-division").modal('hide');
    location.reload();
}

function addTeam(){

    console.log($("#tbody-team-players").find('tr'));

    /*
    if($("#tbody-team-players").find('tr').length < 2 ){
        alert("La squadra deve contenere almeno 2 giocatori");
        return;
    }

    if($("#tbody-team-players").find('tr').length > 3 ){
        alert("La squadra deve contenere una sola riserva");
        return;
    }
    */
    var id_club = null;
    if( $('#id_club').length )
        id_club = $("#modal-add-division-team #id_club").val();
    
    console.log("id_club", id_club);

    $.ajax({
        url: '/admin/tournaments/subscribe',
        data:{
            "_token": "{{ csrf_token() }}",        
            id_tournament: $("#modal-add-division-team #id_tournament").val(),
            id_zone: $("#modal-add-division-team #id_zone").val(),
            id_club: id_club,
            id_category_type: $("#modal-add-division-team #id_category_type").val(),
            team_name: $("#modal-add-division-team #team_name").val(),
            players: add_players            
        },
        type: 'post',
        dataType: 'json',
        success: function(data){      

            console.log(data);      
            
            add_team_group = null;
            add_players = [];    

            if(data.status == 'ok'){
                $("#modal-add-group-team").modal('hide');
                document.location.reload();
            }
        }
    });
}


function addTeamPlayer(){        
    var edition_type = $("#edition_type").val();
    var id_player = $("#sel_player").val();
    
    var tot_starters = 2;
    if( edition_type == 2 ){
        tot_starters = 5;
    }
    
    $.ajax({
        url: '/admin/player/'+id_player+'/get',
        type: 'get',
        dataType: 'json',
        success: function(data){            
            $("#sel_player").val('');
            $("#search-input").val('');

            console.log(data);            

            var avatar = '';

            for(var i=0; i<data.metas.length;i++){
                if(data.metas[i]){
                    if(data.metas[i].meta == 'avatar'){
                        avatar = data.metas[i].meta_value;
                    }
                }
            }

            var tipo = 'riserva';
            if( $("#tbody-team-players tr").length < tot_starters ){
                tipo = 'titolare';
            }

            add_players.push(id_player);            

            var row = '<tr id="tr_player_'+data.id+'">';
            //row += '<td><img src="{!! env('APP_URL') !!}/storage/'+avatar+'" style="width: 30px; height: 30px" class="img-circle"></td>';
            row += '<td>'+data.name+' '+data.surname+'<input type="hidden" name="id_player" value="'+data.id+'"></td>';
            row += '<td>'+data.email+'</td>';            
            row += '<td id="tipo_giocatore">'+tipo+'</td>';            
            row += '<td><button type="button" class="btn btn-danger" onclick="removePlayer('+data.id+')"><i class="fa fa-trash-o"></i></button></td>';        
            row += '</tr>';

            $("#tbody-team-players").append(row);            
        }
    });
}

function selectPlayer (id_player){    
    $("#sel_player").val(id_player);    
    addTeamPlayer();
}

function removePlayer(id_player){
    $("#tr_player_"+id_player).remove();
    $("#search-input").val('');
    $("#tbody-team-players tr").each(function(k, val){
        if(k<=1){
            $(this).find('#tipo_giocatore').html('titolare');
        }else{
            $(this).find('#tipo_giocatore').html('riserva');
        }
    });
}

function deleteDivision(id_division){
    if( confirm("Confermare la cancellazione?") ){

        $.ajax({
            url: '/admin/division/'+id_division+'/remove',
            data:{
                "_token": "{{ csrf_token() }}",                    
            },
            type: 'delete',
            dataType: 'json',
            success: function(data){         
                console.log("data", data);
                location.reload();
            }   
        });

    }
}

function createDivision(id_tournament, id_zone, id_category_type, id_category){
    $.ajax({
        url: '/admin/division/create',
        data:{
            "_token": "{{ csrf_token() }}",                    
            id_tournament: id_tournament,
            id_zone: id_zone,
            id_category_type: id_category_type,
            id_category: id_category
        },
        type: 'post',
        dataType: 'json',
        success: function(data){         
            $("#btn_"+id_tournament+"_"+id_zone+"_"+id_category_type+"_"+id_category).parent().html('creata');
        }   
    });
}

function removeSubscription(id_subscription, id_division, edition_type){
    var url = '/admin/subscription/'+id_subscription+'/remove';
    if( edition_type == 2 ){
        url = '/admin/macro_subscription/'+id_subscription+'/remove';
    }
    
    if( confirm("Confermare la cancellazione?") ){
        $.ajax({
            url: url,
            data:{
                "_token": "{{ csrf_token() }}",                    
                id_subscription: id_subscription            
            },
            type: 'delete',
            dataType: 'json',
            success: function(data){     
                if( data.status == 'ok'){
                    $("#table_subscription_"+id_subscription).remove();
                    $("#counter_subscription_"+id_division).text( parseInt($("#counter_subscription_"+id_division).text())-1 );
                }
            }   
        });
    }
}
</script>
@endsection