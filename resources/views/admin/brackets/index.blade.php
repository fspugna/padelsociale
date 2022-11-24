@extends('admin.layouts.app')

@section('content')

    <section class="content-header">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.editions.index') }}">Tornei</a></li>
                <li><a href="/admin/editions/{!! $tournament->edition->id !!}/edit">Edizione torneo</a></li>
            </ol>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <h1>
                    Tabelloni {!! $edition->edition_name !!}
                </h1>
            </div>
        </div>
    </section>
    <div class="content">

        <button class="btn btn-success" onClick="addBracket();">Aggiungi categoria</button>

        <table class="table" style="background: #fff;">
        @foreach($arr_brackets as $zones)
            @foreach($zones as $zone)
                <tr>
                    <td><h3>{!! $zone['name'] !!}</h3></td>
                    <td>
                        <table style="width: 100%;">
                        @foreach($zone['category_types'] as $categoryType)
                        <tr>
                            <td><h3>{!! $categoryType['name'] !!}</h3></td>
                        </tr>
                        <tr>
                            <td>
                                <table class="table">
                                    @foreach($categoryType['categories'] as $category)
                                    <tr>
                                        <td width="60%">{!! $category['name'] !!}</td>
                                        <td style="text-align: right">
                                            <table class="table">

                                                @foreach($category['values'] as $bracket)
                                                <tr>
                                                    <td >
                                                        <div class="btn-group">
                                                            @if($bracket->generated)
                                                                @if( in_array($bracket->tournament->edition->edition_type, [0, 1] ) )
                                                                <a class="btn btn-sm btn-primary pull-right" href="/admin/bracket/{!! $bracket->id !!}/show">Mostra</a>
                                                                @elseif( $bracket->tournament->edition->edition_type == 2 )
                                                                <a class="btn btn-sm btn-primary pull-right" href="/admin/bracket/{!! $bracket->id !!}/show_macro">Mostra</a>
                                                                @endif
                                                            @if($bracket->flag_online == 1)
                                                                <a class="btn btn-sm btn-success pull-right" href="javascript:void(0)" onClick="bracketOnline({!! $bracket->id !!}, 'false')">Online</a>
                                                            @elseif($bracket->flag_online == 0)
                                                                <a class="btn btn-sm btn-warning pull-right" href="javascript:void(0)" onClick="bracketOnline({!! $bracket->id !!}, 'true')">Offline</a>
                                                            @endif
                                                        @else
                                                            <a href="/admin/bracket/{!! $bracket->id !!}/prepare" class="btn btn-sm btn-primary pull-right"><i class="fa fa-sitemap"></i> Crea tabellone</a>
                                                        @endif
                                                        <a class="btn btn-sm btn-danger pull-right" href="javascript:void(0)" onClick="delBracket({!! $bracket->id !!})">Elimina Categoria</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </table>
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                        @endforeach
                        </table>
                    </td>
                </tr>
            @endforeach
        @endforeach
        </table>

    </div>


    <div id="modal-team-bracket" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Torneo</h4>
                </div>
                <div class="modal-body">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="input-group">
                            <form class="typeahead" role="search">
                                <div class="form-group">
                                    <input type="search" name="q" id="search-input" class="form-control search-input" placeholder="Aggiungi giocatore" autocomplete="off">
                                </div>
                            </form>
                            <div id="team-player-add" class="input-group-btn">
                                <button type="button" class="btn btn-primary" style="margin: 0"" onClick="addTeamPlayer()"><i class="fa fa-plus"></i> Aggiungi</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <!-- Name Field -->
                        <table class="table table-striped">
                            <thead>
                                <td></td>
                                <td>Giocatore</td>
                                <td>Email</td>
                            </thead>
                            <tbody id="tbody-team-players">

                            </tbody>
                        </table>
                    </div>
                </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" id="sel_player" value="">
                    <input type="hidden" name="id_bracket" id="id_bracket" value="">
                    <button type="button" class="btn btn-primary" onClick="addTeam()">{!! trans('labels.save') !!}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{!! trans('labels.cancel') !!}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <div id="modal-add-bracket" class="modal fade" tabindex="-1" role="dialog">
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
                                <tbody id="tbody-brackets">
                                    @foreach( $tournament->edition->zones as $editionZone )
                                        @foreach( $tournament->edition->categoryTypes as $editionCategoryType )
                                            @foreach( $tournament->edition->categories as $editionCategory )
                                                <tr>
                                                    <td>{!! $editionZone->zone->name !!}</td>
                                                    <td>{!! $editionCategoryType->categoryType->name !!}</td>
                                                    <td>{!! $editionCategory->category->name !!}</td>
                                                    <td>
                                                        @if( \App\Models\Bracket::where('id_tournament', '=', $tournament->id)->where('id_zone', '=', $editionZone->id_zone)->where('id_category_type', '=', $editionCategoryType->id_category_type)->where('id_category', '=', $editionCategory->id_category)->first() )
                                                            Esiste
                                                        @else
                                                            <button id="btn_{!! $tournament->id !!}_{!! $editionZone->id_zone !!}_{!! $editionCategoryType->id_category_type !!}_{!! $editionCategory->id_category !!}" class="btn btn-success" onClick="createBracket({!! $tournament->id !!}, {!! $editionZone->id_zone !!} , {!! $editionCategoryType->id_category_type !!}, {!! $editionCategory->id_category !!});">Crea</button>
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
                    <button type="button" class="btn btn-default" onClick="closeModalAddBracket()">Chiudi</button>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection

@section('scripts')
<script>

var add_players = [];
var add_team_bracket = null;

function openClose(id){
    $('#teams_'+id).toggleClass('hidden');

    if( $('#teams_'+id).hasClass('hidden') ){
        $('#btn-open-'+id).html('<i class="fa fa-edit"></i> Modifica squadre');
    }else{
        $('#btn-open-'+id).html('Chiudi');

    }
}

function addTeamToBracket(id_bracket){
    add_team_bracket = id_bracket;
    add_players = [];
    $("#modal-team-bracket").modal('show');
}

function addTeam(){

    if($("#tbody-team-players").find('tr').length !== 2){
        alert("La squadra deve contenere 2 giocatori");
        return;
    }

    $.ajax({
        url: '/admin/brackets/teams/add',
        data:{
            "_token": "{{ csrf_token() }}",
            id_bracket: add_team_bracket,
            players: add_players
        },
        type: 'post',
        dataType: 'json',
        success: function(data){
            console.log(data);
            add_team_bracket = id_bracket;
            add_players = [];

            if(data.status == 'ok'){
                $("#modal-team-bracket").modal('hide');
                document.location.reload();
            }
        }
    });
}

function delBracket(id_bracket){

    if( !confirm("Eliminare il tabellone selezionato?") ){
        return;
    }

    $.ajax({
        url: '/admin/bracket/'+id_bracket+'/delete',
        data:{
            "_token": "{{ csrf_token() }}",
            id_bracket: id_bracket,
        },
        type: 'post',
        dataType: 'json',
        success: function(data){

            alert(data.msg);
            if(data.status == 'OK')
                document.location.reload();

        }
    });
}

function removeTeamFromPhase(id_phase, id_team){

    if(confirm("Eliminare la squadra?")){
        $.ajax({
            url: '/admin/brackets/teams/remove',
            data:{
                "_token": "{{ csrf_token() }}",
                id_phase: id_phase,
                id_team: id_team
            },
            type: 'post',
            dataType: 'json',
            success: function(data){
                if(data.status == 'ok'){
                    document.location.reload();
                }
            }
        });
    }
}

function addTeamPlayer(){

    if($("#tbody-team-players").find('tr').length == 2){
        alert("La squadra deve contenere 2 giocatori");
        return;
    }

    var id_player = $("#sel_player").val();
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

            var row = '<tr id="tr_player_'+data.id+'">';
            //row += '<td><img src="{!! env('APP_URL') !!}/storage/'+avatar+'" style="width: 30px; height: 30px" class="img-circle"></td>';
            row += '<td>'+data.name+'<input type="hidden" name="id_player" value="'+data.id+'"></td>';
            row += '<td>'+data.email+'</td>';
            row += '<td><button type="button" class="btn btn-danger" onclick="removePlayer('+data.id+')"><i class="fa fa-trash-o"></i></button></td>';
            row += '</tr>';

            $("#tbody-team-players").append(row);
        }
    });
}

function selectPlayer (id_player){
    $("#sel_player").val(id_player);
    add_players.push(id_player);
}

function removePlayer(id_player){
    $("#tr_player_"+id_player).remove();
    $("#search-input").val('');
}

$(document).ready(function($) {


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

        display: function(data){ return data.name },

        // the key from the array we want to display (name,id,email,etc...)
        templates: {

            empty: [
                '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
            ],
            header: [
                '<div class="list-group search-results-dropdown">'
            ],
            suggestion: function (data) {
                return '<a href="#" onClick="selectPlayer('+data.id+')" class="list-group-item">' + data.name + ' <br> ' + data.email + '</a>'
            }
        }
    });
});

function addBracket(){
    $("#modal-add-bracket").modal('show');
}

function createBracket(id_tournament, id_zone, id_category_type, id_category){
    $.ajax({
        url: '/admin/bracket/create',
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

function closeModalAddBracket(){
    $("#modal-add-bracket").modal('hide');
    location.reload();
}

function bracketOnline(id_bracket, flag_online){
    $.ajax({
        url: '/admin/brackets/online',
        data:{
            "_token": "{{ csrf_token() }}",
            id_bracket: id_bracket,
            flag_online: flag_online
        },
        type: 'post',
        dataType: 'json',
        success: function(data){
            document.location.reload()
        }
    });
}
</script>


@endsection

