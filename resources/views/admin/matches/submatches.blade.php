<style>
    .table-submatch td{ border: 0 !important; }
    .table-submatch { border: 1px solid #e2e2e2 }
</style>
<table class="table">
    <tr>
        <td colspan="3" class="text-center" style="border: 0">
            <span style="font-weight: 800">{!! $macroMatch->team1->name !!} - {!! $macroMatch->team2->name !!}</span>
            <br>
            Incontri Giocatori  @if($edit_mode) modifica @endif
            
            <div class="row">
                
                <div class="col-sm-4 text-center">
                    <button type="button" class="btn btn-primary" onclick="macroScore({!! $macroMatch->id !!})">Risultati rapidi</button>
                </div>
                <div class="col-sm-8 text-center">
                    <div class="input-group text-center">                        
                        <input type="number" min="1" step="1" value="1" name="add_n_matches_{!! $macroMatch->id !!}" id="add_n_matches_{!! $macroMatch->id !!}" class="form-control pull-right" style="width: 80px">
                        <div class="input-group-btn">
                            <a href="javascript:void(0);" onClick="addSubMatch({!! $macroMatch->id !!})" class="btn btn-success"><i class="fa fa-arrow-left"></i> Aggiungi N. Incontri Giocatori</a>
                        </div>
                    </div>
                </div>                
            </div>                                    
        </td>
    </tr>
    <tr>
        <td colspan="3" class="text-center" style="border: 0">
            
                @foreach($macroMatch->subMatches as $numMatch => $match)
                <table class="table table-responsive table-submatch" id="table_submatch_{!! $match->id !!}">
                <tr>
                    <td colspan="3" class="text-center">
                        Incontro Giocatori <span class="num_submatch" id="num_submatch_{!! $match->id !!}">{!! $numMatch+1 !!}</span>
                    </td>
                </tr>
                <tr>                                                
                    <td style="border: none">                           
                        
                        <input type="hidden" name="submatch_{!! $match->id !!}_team1_id" value="{!! $match->id_team1 !!}">                                                                                                        
    
                        @if( !isset($allScores[$match->id]) || empty($allScores[$match->id]) ) 

                            @if( !isset($match->team1) || count($match->team1->players) == 0 )

                                <select name="submatch_{!! $match->id !!}_team1_1" class="form-control">
                                    <option value="">Seleziona Giocatore</option>
                                    @foreach($macroMatch->team1->players as $player)
                                    <option value="{!! $player->player->id !!}">{!! $player->player->name !!} {!! $player->player->surname !!}</option>
                                    @endforeach
                                </select>

                                <select name="submatch_{!! $match->id !!}_team1_2" class="form-control">
                                    <option value="">Seleziona Giocatore</option>
                                    @foreach($macroMatch->team1->players as $player)
                                    <option value="{!! $player->player->id !!}">{!! $player->player->name !!} {!! $player->player->surname !!}</option>
                                    @endforeach
                                </select>

                            @else

                                @foreach($match->team1->players as $index => $player)                                                       
                                    <select name="submatch_{!! $match->id !!}_team1_{!! $index+1 !!}" class="form-control">
                                        <option value="">Seleziona Giocatore</option>
                                        @foreach($macroMatch->team1->players as $macroPlayer)
                                        <option value="{!! $macroPlayer->player->id !!}" 
                                                @if($macroPlayer->player->id == $player->id_player)
                                                selected 
                                                @endif
                                                >{!! $macroPlayer->player->name !!} {!! $macroPlayer->player->surname !!}</option>
                                        @endforeach
                                    </select>
                                @endforeach

                            @endif
                            
                            <br>
                            
                        @else
                            
                            @if( isset($match_players[$match->id]['team1']) )
                                <table class="table table-bordered">
                                @foreach( $match_players[$match->id]['team1'] as $k => $matchPlayer )                                
                                <tr><td>{!! $matchPlayer->player->name !!} {!! $matchPlayer->player->surname !!}</td></tr>                                    
                                @endforeach
                                </table>                            
                            @endif

                        @endif                        
                        
                        @if( isset($match->id) && isset($match->team1->id) && isset($match->team2->id) )
                        <a class="pull-right" href="javascript:void(0);" onClick="vittoriaATavolino({!! $match->id !!}, {!! $match->team1->id !!}, {!! $match->team2->id !!})">Assegna vittoria a tavolino <i class="fa fa-trophy"></i></button></a>
                        @endif 
                        
                    </td>
                    <td class="text-center">
                        VS
                        <br>
                        <a href="javascript:void(0);" onClick="deleteMatch({!! $match->id !!}, {!! $macroMatch->id !!})"><i class="fa fa-trash"></i> Elimina</a>
                    </td>
                    <td>

                        <input type="hidden" name="submatch_{!! $match->id !!}_team2_id" value="{!! $match->id_team2 !!}">
                                                
                        @if( !isset($allScores[$match->id]) || empty($allScores[$match->id]) ) 
                            @if( !isset($match->team2) || count($match->team2->players) == 0 )
                                <select name="submatch_{!! $match->id !!}_team2_1" class="form-control">
                                    <option value="">Seleziona Giocatore</option>
                                    @foreach($macroMatch->team2->players as $player)
                                    <option value="{!! $player->player->id !!}">{!! $player->player->name !!} {!! $player->player->surname !!}</option>
                                    @endforeach
                                </select>

                                <select name="submatch_{!! $match->id !!}_team2_2" class="form-control">
                                    <option value="">Seleziona Giocatore</option>
                                    @foreach($macroMatch->team2->players as $player)
                                    <option value="{!! $player->player->id !!}">{!! $player->player->name !!} {!! $player->player->surname !!}</option>
                                    @endforeach
                                </select>
                            @else

                                @foreach($match->team2->players as $index => $player)                                                       
                                    <select name="submatch_{!! $match->id !!}_team2_{!! $index+1 !!}" class="form-control">
                                        <option value="">Seleziona Giocatore</option>
                                        @foreach($macroMatch->team2->players as $macroPlayer)
                                        <option value="{!! $macroPlayer->player->id !!}" 
                                                @if($macroPlayer->player->id == $player->id_player)
                                                selected 
                                                @endif
                                                >{!! $macroPlayer->player->name !!} {!! $macroPlayer->player->surname !!}</option>
                                        @endforeach
                                    </select>
                                @endforeach
                            @endif
                            
                            <br>
                            
                        @else
                            
                            @if( isset($match_players[$match->id]['team2']) )
                                <table class="table table-bordered">
                                @foreach( $match_players[$match->id]['team2'] as $k => $matchPlayer )                                
                                <tr><td>{!! $matchPlayer->player->name !!} {!! $matchPlayer->player->surname !!}</td></tr>                                    
                                @endforeach
                                </table>                            
                            @endif
                        @endif                                                
                        
                        @if( isset($match->id) && isset($match->team1->id) && isset($match->team2->id) )
                        <a class="pull-left" href="javascript:void(0);" onClick="vittoriaATavolino({!! $match->id !!}, {!! $match->team2->id !!}, {!! $match->team1->id !!})"><i class="fa fa-trophy"></i> Assegna vittoria a tavolino</button></a>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="text-center" colspan="3">                        
                        @include('admin.matches.create')
                    </td>
                </tr>
                </table>
                @endforeach
            
        </td>
    </tr>
</table>

<div id="modal-score-{!! $macroMatch->id !!}" class="modal fade" tabindex="-1" role="dialog">            
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Risultati mini-match</h4>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow: auto">     
                <form id="frm_all_scores">
                    <table class="table table-striped">
                    @foreach($macroMatch->subMatches as $numMatch => $match)
                    <tr>
                        <td style="width: 35%">                        
                            <table>
                                @php
                                $players = \App\Models\TeamPlayer::where('id_team', '=', $match->id_team1)->get();
                                @endphp 
                                @foreach($players as $teamPlayer)
                                <tr>
                                    <td>
                                        {!! $teamPlayer->player->name !!} {!! $teamPlayer->player->surname !!}
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </td>
                        <td class="text-center" style="width: 30%">
                            <strong>Risultato</strong>
                            <table class="table">
                                <tr>
                                    <td style="width: 50%"><input class="form-control" type="number" min="0" id="score_team1_{!! $match->id_team1 !!}_match_{!! $match->id !!}" style="width: 100%;"></td>
                                    <td style="width: 50%"><input class="form-control" type="number" min="0" id="score_team2_{!! $match->id_team2 !!}_match_{!! $match->id !!}" style="width: 100%;"></td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table class="pull-right">
                                @php
                                $players = \App\Models\TeamPlayer::where('id_team', '=', $match->id_team2)->get();
                                @endphp 
                                @foreach($players as $teamPlayer)
                                <tr>
                                    <td class="text-right">
                                        {!! $teamPlayer->player->name !!} {!! $teamPlayer->player->surname !!}
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                    @endforeach
                    </table>                                    
                </form>
                <button type="button" class="btn btn-primary" onclick="saveAllScores({!! $macroMatch->id !!});">Salva tutto</button>
            </div>        
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@section('scripts')
@parent
<script>
function saveAllScores(id_macro_match){
    
   var data = {
       "_token": "{{ csrf_token() }}"
   };
   
   var scores = document.getElementById('modal-score-'+id_macro_match).querySelectorAll('input[type=number]');
   for(var i=0; i<scores.length;i++){
       console.log(scores[i].id, scores[i].value);
       data[scores[i].id] = scores[i].value;
   }
    
    $.ajax({
        url: '/admin/score/macro/'+id_macro_match+'/store',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function(res){   
            window.location.reload();
        } 
    });
}
    
function macroScore(id_macro_match){
    $("#modal-score-"+id_macro_match).modal('show');
}

function deleteMatch(id_match, id_macro_match){
    if( confirm("Vuoi eliminare l'incontro selezionato?") ){
        $.ajax({
            url: '/admin/matches/'+id_match+'/deleteAjax',
            data: { "_token": "{{ csrf_token() }}" },
            type: 'post',
            dataType: 'json',
            success: function(res){   
                console.log("schedule res: " , res);
                if(res.status == 'ok'){                
                    $("#table_submatch_"+id_match).remove();                
                    $("#panel_submatches_"+id_macro_match+" .table-submatch .num_submatch").each(function(k, val){
                        console.log($(this).attr("id"));
                        $(this).text((k+1));
                    });                
                }else if(res.status == 'error'){
                    alert(res.msg);
                }
            } 
        });    
    }
}

function openScheduleMatch (id_match) {    
    console.log("openScheduleMatch", id_match);
    $("#modal-schedule #id_match").val(id_match);
    $("#modal-schedule").modal('show');
}

function openScoreMatch (id_match, id_team1, id_team2) {
        
    $("#modal-score #score-id-match").val(id_match);
    $("#modal-score #score-id-team1").val(id_team1);
    $("#modal-score #score-id-team2").val(id_team2);
    
    $("#modal-score").modal('show');
    
}

function editScoreMatch (id_match, id_team1, id_team2) {
        
        $("#modal-score #score-id-match").val(id_match);
        $("#modal-score #score-id-team1").val(id_team1);
        $("#modal-score #score-id-team2").val(id_team2);
        
        $("#modal-score").modal('show');
        
    }

function scheduleForm(){
    var id_match = $("#modal-schedule #id_match").val();

    var data = {    
        "_token": "{{ csrf_token() }}",        
        id_match : id_match,        
        match_date: $("#modal-schedule #match_date input").val(),        
        match_hours: $("#modal-schedule #match_hours input").val(),        
        match_club: $("#modal-schedule #match_club").val(),        
    }

    $.ajax({
        url: '/admin/matches/schedule',
        type:'post',
        data: data,
        dataType: 'json',
        success: function(res){   
            console.log("schedule res: " , res);
            if(res.status == 'ok'){
                $("#btn-schedule-"+id_match).remove();
                $("#modal-schedule").modal('hide');
                location.reload();
            }else if(res.status == 'error'){
                alert(res.msg);
            }
        }
    });
}

function addSubMatch(id_macro_match){
    var num_matches = $("#add_n_matches_"+id_macro_match).val();
    console.log("num_matches", num_matches);
    var data = {
            "_token": "{{ csrf_token() }}",     
            num_matches: num_matches
    }
    
    $.ajax({
        url: '/admin/macro_match/'+id_macro_match+'/sub_match/add',
        data: data,
        type: 'post', 
        dataType: 'json',
        success: function(res){   
            console.log("schedule res: " , res);
            if(res.status == 'ok'){                
                location.reload();
            }else if(res.status == 'error'){
                alert(res.msg);
            }
        }
    })
    
}

$(document).ready(function (){    
    $('#match_date').datepicker({
        language: 'it',
        autoclose: true
    });    

    $('#match_hours').datetimepicker({
        format: 'HH:mm'
    }); 

    $("#match-schedule-form").submit(function(event){

        event.preventDefault();

        
    });
});

</script>
@endsection