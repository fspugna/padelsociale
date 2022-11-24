@extends('admin.layouts.app')

@section('css')
<style>
.twitter-typeahead, .tt-hint, .tt-input, .tt-menu { width: 100%; margin-top: 10px; }
.tt-dataset{ margin-top: -30px; }
</style>
@endsection

@section('content')
    <section class="content-header">
        <div class="row">
            <ol class="breadcrumb">                
                <li><a href="/admin/editions/{!! $subscription->tournament->edition->id !!}/edit"><i class="fa fa-dashboard"></i> Edizione</a></li>      
                <li><a href="{{ route('admin.subscriptions', ['id_tournament' => $subscription->tournament->id]) }}">Iscrizioni</a></li>                  
            </ol>            
        </div>
        <div class="row">
            <div class="col-sm-12">
                <h1>
                    {{ $team->name }}
                </h1>
            </div>
        </div>        
    </section>
    
   <div class="content">
       @include('adminlte-templates::common.errors')
       {!! Form::open(['route' => ['admin.teams.update', $team->id], 'method' => 'PUT']) !!}
       <div class="box box-primary">
           <div class="box-body">                                    
                @include('admin.teams.fields')                                            
           </div>
           
           <div class="box-footer">
               <button type="submit" class="btn btn-success">Salva</button>
           </div>
           
       </div>
       {!! Form::close() !!}
   </div>
@endsection

@section('scripts')
<script>

showModalPlayer = function(id_team){
    $("#modal-player #id_team").val(id_team);
    $("#modal-player").modal('show');
}

selectPlayer = function(id_player, name_player){
    
    $("#id_player").val(id_player);   
    $("#search-input").val(name_player);
    addTeamPlayer();
}

addTeamPlayer = function(){        
    
    var data = {
        "_token": "{{ csrf_token() }}",                
        id_team: $("#team-player-add #id_team").val(),
        id_player: $("#team-player-add #id_player").val(),        
    }   
    
    $.ajax({
        url: '/admin/teamPlayers',
        data: data,
        type: 'post',
        success: function(data){
            if(data.status === 'ok'){
                document.location.reload();
            }
        },
        error: function(data){
            alert("Non puoi aggiungere il giocatore");
        }
    });
}

removeTeamPlayer = function(id_team_player){
    if(confirm('Eliminare il giocatore selezionato?')){

        var data = {
            "_token": "{{ csrf_token() }}",                            
        }  
        
        $.ajax({
            url: '/admin/teamPlayers/'+id_team_player,
            data: data,
            type: 'DELETE',
            success: function(data){
                if(data.status === 'ok'){
                    document.location.reload();
                }
            }
        });
    }
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
                return '<a href="#" onClick="selectPlayer('+data.id+', \''+data.name.replace("'", "\\'") + ' ' + data.surname.replace("'", "\\'") +'\')" class="list-group-item">' + data.name + ' ' + data.surname + ' <br> ' + data.email + '</a>'
            }
        }
    });
});
</script>
@endsection