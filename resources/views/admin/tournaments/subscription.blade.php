@extends('admin.layouts.app')

@section('css')
<style>
.twitter-typeahead, .tt-hint, .tt-input, .tt-menu { width: 100%; margin-top: 10px; }
</style>
@endsection

@section('content')

{!! Form::open(['route' => 'admin.subscribe']) !!}
    <section class="content-header">
        <h1>
            Iscrizione al torneo
        </h1>
    </section>
    <div class="content">
        
        @include('adminlte-templates::common.errors')

        <h1>{{ $tournament->edition->edition_name }}</h1>
        <h2>{{ $tournament->name }}</h2>
        
        <div class="box box-primary">
           <div class="box-body">
                <div class="row">
                    <div class="col-xs-6">

                        <div class="form-group col-sm-6">
                        {!! Form::label('id_zone', 'Zona') !!}                        
                        {!! Form::select('id_zone', $zones, null,  ['class' => 'form-control', 'required' => true]) !!}
                        </div>

                    </div>

                    <div class="col-xs-6">

                        <div class="form-group col-sm-6">
                        {!! Form::label('id_category_type', 'Tipologia') !!}                        
                        {!! Form::select('id_category_type', $categoryTypes, null,  ['class' => 'form-control', 'required' => true]) !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div id="box-clubs" class="box box-primary hidden">
           <div class="box-body">
                <div class="row">
                    <div class="col-xs-12">                                            
                        <h2>Circoli della zona</h2>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Circolo</th>
                                    <th>Indirizzo</th>
                                </tr>                            
                            </thead>
                            <tbody id="tbody-clubs">                            
                            </tbody>
                        </table>                        

                    </div>
                </div>
            </div>
        </div>
        
        <div class="box box-primary">
           <div class="box-body">
                <div class="row">
                    <div class="col-xs-12">
                        <h2>Componi la tua squadra</h2>
                        @include('admin.teams.fields')
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Submit Field -->
            <div class="form-group col-sm-12">
                {!! Form::submit('Iscriviti al torneo', ['name' => 'btn-subscribe', 'class' => 'btn btn-primary btn-block btn-lg']) !!}
                <input type="submit" name="btn-cancel-subscription" class="btn btn-default btn-block btn-lg" value="Annulla" />
            </div>
        </div>
    </div>
    
</section>
{!! Form::close() !!}

@endsection

@section('scripts')
<script>


showModalPlayer = function(id_team){
    $("#modal-player #id_team").val(id_team);
    $("#modal-player").modal('show');
}

selectPlayer = function(id_player){
    $("#id_player").val(id_player);   
}

addTeamPlayer = function(){

    var data = {
        "_token": "{{ csrf_token() }}",        
        id_tournament: $("#team-player-add #id_tournament").val(),
        id_team: $("#team-player-add #id_team").val(),
        id_player: $("#team-player-add #id_player").val(),
        starter: 0
    }
    
    $.ajax({
        url: '/admin/teamPlayers',
        data: data,
        type: 'post',
        success: function(data){
            if(data.status === 'ok'){
                var row = "<tr id=\"player_"+data.player.id+"\">";
                row += "<td><img src=\"https://via.placeholder.com/60\" class=\"img-circle\" alt=\"User Image\"></td>";
                row += "<td>" + data.player.player.name + " " + data.player.player.surname + "</td>";
                row += "<td>" + data.player.player.email + "</td>";
                row += "<td>";
                if( data.player.starter ){
                    row += "<label class=\"label label-success\">Titolare</label>";
                }else{
                    row += "<label class=\"label label-danger\">Riserva</label>";
                }
                row += "</td>";
                row += "<td><button class=\"btn btn-danger\" onClick=\"removeTeamPlayer(" + data.player.id + ")\"><i class=\"fa fa-trash\"></i></button></td>";
                row += "</tr>";
                
                $("#tbody-team-players").append(row);
            }else{
                alert(data.msg);
            }
        }
    });
}

removeTeamPlayer = function(id_player){
    if( confirm('Rimuovere il giocatore selezionato?') ){
        $.ajax({
            url: '/admin/teamPlayers/'+id_player,    
            type: 'delete',
            data: {
                "_token": "{{ csrf_token() }}"
            },
            success: function(data){
                if(data.status == 'ok'){
                    console.log("remove " + "tr#player_"+id_player);
                    $("#tbody-team-players tr#player_"+id_player).remove();
                }else{
                    alert(data.msg);
                }
            }
        });
    }
}

$(document).ready(function(){

    $('#id_zone').change(function(){
        $.ajax({
            url: '/admin/' + $('#id_zone').val() + '/clubs',
            type: 'get',
            success: function(data){
                console.log(data);
                var righe = '';
                if(data.length){
                    for(var i=0; i<data.length; i++){
                        righe += '<tr><td>' + data[i].name + '</td><td>' + data[i].address + '</td></tr>';
                    }
                    $('#box-clubs').removeClass('hidden');
                }else{
                    $('#box-clubs').addClass('hidden');
                }

                $('#tbody-clubs').html(righe);
            }
        })
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
                return '<a onClick="selectPlayer('+data.id+')" class="list-group-item">' + data.name + ' <br> ' + data.email + '</a>'
            }
        }
    });
});
</script>
@endsection