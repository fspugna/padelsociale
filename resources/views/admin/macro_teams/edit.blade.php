@extends('admin.layouts.app')

@section('css')
<style>
    .twitter-typeahead{width: 100%}
    .tt-menu{width: 100%; margin-top: -20px}
</style>
@endsection

@section('content')
<section class="content-header">
    <h1>
        Squadra {{ $macroTeam->name }}
    </h1>
</section>

<div class="content">
    {!! Form::model($macroTeam, ['route' => ['admin.macro_team.update'], 'method' => 'post']) !!}
    @include('adminlte-templates::common.errors')
    @include('flash::message')       
    
    <div class="row">
        <div class="col-sm-6">    
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">

                        <div class="form-group col-sm-12">
                            {!! Form::label('name', 'Nome squadra') !!}
                            {!! Form::text('name', $macroTeam->name, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('id_club', 'Circolo') !!}
                            {!! Form::select('id_club', $clubs, $macroTeam->id_club, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('info_match_home', 'Giorno e ora partita in casa') !!}
                            {!! Form::text('info_match_home', $macroTeam->info_match_home, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('captain', 'Capitano') !!}
                            {!! Form::text('captain', $macroTeam->captain, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('tel_captain', 'Contatto telefonico della squadra') !!}
                            {!! Form::text('tel_captain', $macroTeam->tel_captain, ['class' => 'form-control']) !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">    
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3>Aggiungi giocatore</h3>
                        </div>
                        <div class="col-sm-12">
                            <input type="hidden" id="sel_player">
                            <input class='search-input form-control'>
                        </div>              
                    </div>              
                </div>
            </div>
            
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3>Giocatori</h3>
                        </div>

                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table" id="players_list">
                                    <thead>
                                        <tr>
                                            <th>Elimina</th>
                                            <th>Giocatore</th>
                                            <th>Titolare</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                    
                                        @foreach($macroTeam->players as $teamPlayer)
                                        <tr>
                                            <td>
                                                <button type="submit" name="btn_del_player" value="{!! $macroTeam->id !!}-{!! $teamPlayer->player->id !!}" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></button> 
                                            </td>
                                            <td>
                                                {!! $teamPlayer->player->name !!} {!! $teamPlayer->player->surname !!}
                                            </td>
                                            <td>
                                                <input type="checkbox" name="starter_{{ $teamPlayer->player->id }}" 
                                                @if( $teamPlayer->starter ) checked @endif>
                                            </td>
                                        </tr>
                                        @endforeach          
                                    </tbody>
                                </table>                                      
                            </div>
                        </div>
                    </div>            
                </div>
            </div>        
        </div>            
    </div>               
    
    <input type="hidden" name="id_macro_team" value="{!! $macroTeam->id !!}">
    <button type="submit" name="btn_save_macro_team" value="save" class="btn btn-primary">Salva squadra</button>            
    {!! Form::close() !!}
</div>

@endsection

@section('scripts')
<script>

    var add_players = [];
    var add_team_group = null;    


    $(document).ready(function () {

        // Set the Options for "Bloodhound" suggestion engine
        var engine = new Bloodhound({
            remote: {
                url: '/admin/player/search?q=%QUERY%',
                wildcard: '%QUERY%'
            },
            datumTokenizer: Bloodhound.tokenizers.whitespace('q'),
            queryTokenizer: Bloodhound.tokenizers.whitespace
        });

        $(".search-input").change(function () {
            $("#player_id").val('');
        });

        $(".search-input").typeahead({
            hint: true,
            highlight: true,
            minLength: 3,
            afterSelect: function (item) {
                console.log("afterSelect", item, this.$element);
                this.$element[0].value = item.value
            }
        }, {
            source: engine.ttAdapter(),

            // This will be appended to "tt-dataset-" to form the class name of the suggestion menu.
            name: 'usersList',

            display: function (data) {
                return data.name + ' ' + data.surname
            },

            // the key from the array we want to display (name,id,email,etc...)
            templates: {

                empty: [
                    '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                ],
                header: [
                    '<div class="list-group search-results-dropdown">'
                ],
                suggestion: function (data) {
                    return '<a href="javascript:void(0);" onClick="selectPlayer(' + data.id + ')" class="list-group-item">' + data.name + ' ' + data.surname + ' <br> ' + data.email + '</a>'
                }
            }
        });
    });


    function addTeamPlayer() {
        
        var id_player = $("#sel_player").val();
        
        $.ajax({
            url: '/admin/player/' + id_player + '/get',
            type: 'get',
            dataType: 'json',
            success: function (data) {
              
                $("#sel_player").val('');
                $(".search-input").val('');
                console.log(data);              
                add_players.push(id_player);
                $("#players_list").append('<tr id="tr_'+id_player+'"><td><button class="btn btn-sm btn-danger" onclick="document.getElementById(\'tr_'+id_player+'\').remove()"><i class="fa fa-trash"></i></button></button></td><td>' + data.name + ' ' + data.surname + '<input type="hidden" name="new_player[]" value="' + data.id + '"></td></tr>')
                                
            }
        });
    }

    function selectPlayer(id_player) {
        $("#sel_player").val(id_player);
        addTeamPlayer();
    }
        
</script>
@endsection