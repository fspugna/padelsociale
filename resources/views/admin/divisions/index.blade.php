@extends('admin.layouts.app')

@section('css')
<style>
    .twitter-typeahead{ width: 100% }
    .tt-menu{ width: 100% }
    .tt-dataset{ margin-top: -20px; }
</style>
@endsection

@section('content')   

<section class="content-header">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.editions.index') }}"><i class="fa fa-dashboard"></i> Edizione</a></li>
            <li><a href="{{ route('admin.subscriptions', ['id_tournament' => $division->tournament->id]) }}">Iscrizioni</a></li>                
        </ol>            
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h1 class="pull-left">            
                Gironi                 
                {!! $division->category->name !!}
                {!! $division->categoryType->name !!}        
                {!! $division->zone->city->name !!}
                {!! $division->zone->name !!}     
            </h1> 
        </div>
    </div>        
</section>



<div class="content">            

    <form action="/admin/division/{!! $division->id !!}/edit">                    

        @include('adminlte-templates::common.errors')

        <div class="row">            
            <div class="col-sm-12">
                @csrf
                <input type="hidden" name="id_division" value="{!! $division->id !!}">

                <div class="form-group">
                @if($division->edit_mode)                                
                <button type="submit" name="btn_add_group" class="btn btn-warning"><i class="fa fa-plus"></i> Aggiungi girone</button>
                <button type="submit" name="btn_edit_groups" class="btn btn-primary"><i class="fa fa-check"></i> Esci da modifica</button>
                <button type="submit" name="btn_save_groups" class="btn btn-success"><i class="fa fa-check"></i> Salva gironi</button>
                @elseif( !$division->edit_mode )
                <button type="submit" name="btn_edit_groups" class="btn btn-warning"><i class="fa fa-edit"></i> Modifica gironi</button>
                @endif
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        @include('flash::message')        

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">

                @if( $division->tournament->edition->edition_type == 1 )
                @include('admin.divisions.table')                    
                @elseif( $division->tournament->edition->edition_type == 2 )
                @include('admin.divisions.table_macro')                    
                @endif

                @if($division->edit_mode)                                
                @include('admin.divisions.teams_not_in_groups')
                @endif
            </div>
        </div>
        <div class="text-center">

        </div>
    </form>       
</div>


<div id="modal-add-group-team" class="modal fade" tabindex="-1" role="dialog">            
    <div class="modal-dialog" role="document">            
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Torneo</h4>
            </div>
            <div class="modal-body">                    
                <div class="row">
                    <div class="col-xs-12">    

                        <form class="typeahead" role="search">
                            <div class="form-group">
                                <input type="search" name="q" id="search-input" class="form-control search-input" placeholder="Aggiungi giocatore" autocomplete="off">
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
                <input type="hidden" name="id_group" id="id_group" value="">
                <button type="button" class="btn btn-primary" onClick="addTeam()">{!! trans('labels.save') !!}</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">{!! trans('labels.cancel') !!}</button>
            </div>
        </div><!-- /.modal-content -->        
    </div><!-- /.modal-dialog -->                
</div><!-- /.modal -->

@endsection


@section('scripts')
<script>

    var add_players = [];
    var add_team_group = null;

    $(document).ready(function () {
        $("[id^=online-]").on('ifChanged', function () {
            var flag_online = $(this).is(':checked');

            var data = {
                "_token": "{{ csrf_token() }}",
                id_group: $(this).attr('id').split('-')[1],
                flag_online: flag_online
            }

            $.ajax({
                url: '/admin/groups/online',
                type: 'post',
                data: data,
                success: function (res) {
                }
            });
        });

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
                    return '<a href="#" onClick="selectPlayer(' + data.id + ')" class="list-group-item">' + data.name + ' ' + data.surname + ' <br> ' + data.email + '</a>'
                }
            }
        });
    });

    function addGroupTeam(id_group) {
        add_team_group = id_group;
        add_players = [];
        $("#modal-add-group-team").modal('show');
    }

    function delGroup(id_group) {
        if (confirm("Eliminare il gruppo selezionato? Attenzione, eliminando il gruppo non sarà più possibile recuperare i dati!")) {
            $.ajax({
                url: '/admin/groups/remove',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id_group: id_group
                },
                type: 'post',
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    document.location.reload();
                }
            });
        }
    }


    function makeCalendar(id_group) {
        if (confirm("Rigenerare il calendario per questo Girone?")) {
            $.ajax({
                url: '/admin/groups/' + id_group + '/calendar/make',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id_group: id_group
                },
                type: 'post',
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if (data.status == 'ok') {
                        alert("IL CALENDARIO E' STATO GENERATO!");
                    }
                }
            });
        }
    }



    function addTeam() {

        console.log($("#tbody-team-players").find('tr'));

        if ($("#tbody-team-players").find('tr').length < 2) {
            alert("La squadra deve contenere almeno 2 giocatori");
            return;
        }

        if ($("#tbody-team-players").find('tr').length > 3) {
            alert("La squadra deve contenere una sola riserva");
            return;
        }

        $.ajax({
            url: '/admin/groups/teams/add',
            data: {
                "_token": "{{ csrf_token() }}",
                id_group: add_team_group,
                players: add_players
            },
            type: 'post',
            dataType: 'json',
            success: function (data) {

                console.log(data);

                add_team_group = null;
                add_players = [];

                if (data.status == 'ok') {
                    $("#modal-add-group-team").modal('hide');
                    document.location.reload();
                }
            }
        });
    }


    function removeTeamFromGroup(id_group, id_team) {

        if (confirm("Eliminare la squadra?")) {
            $.ajax({
                url: '/admin/groups/teams/remove',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id_group: id_group,
                    id_team: id_team
                },
                type: 'post',
                dataType: 'json',
                success: function (data) {
                    if (data.status == 'ok') {
                        document.location.reload();
                    }
                }
            });
        }
    }

    function addTeamPlayer() {

        var id_player = $("#sel_player").val();
        $.ajax({
            url: '/admin/player/' + id_player + '/get',
            type: 'get',
            dataType: 'json',
            success: function (data) {
                $("#sel_player").val('');
                $("#search-input").val('');

                console.log(data);

                var avatar = '';

                for (var i = 0; i < data.metas.length; i++) {
                    if (data.metas[i]) {
                        if (data.metas[i].meta == 'avatar') {
                            avatar = data.metas[i].meta_value;
                        }
                    }
                }

                var tipo = 'riserva';
                if ($("#tbody-team-players tr").length < 2) {
                    tipo = 'titolare';
                }

                add_players.push(id_player);

                var row = '<tr id="tr_player_' + data.id + '">';
                //row += '<td><img src="{!! env('APP_URL') !!}/storage/'+avatar+'" style="width: 30px; height: 30px" class="img-circle"></td>';
                row += '<td>' + data.name + ' ' + data.surname + '<input type="hidden" name="id_player" value="' + data.id + '"></td>';
                row += '<td>' + data.email + '</td>';
                row += '<td id="tipo_giocatore">' + tipo + '</td>';
                row += '<td><button type="button" class="btn btn-danger" onclick="removePlayer(' + data.id + ')"><i class="fa fa-trash-o"></i></button></td>';
                row += '</tr>';

                $("#tbody-team-players").append(row);
            }
        });
    }

    function selectPlayer(id_player) {
        $("#sel_player").val(id_player);
        addTeamPlayer();
    }

    function removePlayer(id_player) {
        $("#tr_player_" + id_player).remove();
        $("#search-input").val('');
        $("#tbody-team-players tr").each(function (k, val) {
            if (k <= 1) {
                $(this).find('#tipo_giocatore').html('titolare');
            } else {
                $(this).find('#tipo_giocatore').html('riserva');
            }
        });
    }
</script>
@endsection