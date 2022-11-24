@extends('admin.layouts.app')

@section('content')

    @include('adminlte-templates::common.errors')

    @if( \App\Models\User::where('id', '=', \Illuminate\Support\Facades\Auth::id() )->first()->id_role == 1 )        
    <form action="/admin/brackets/{!! $bracket->id !!}/edit" method="post">
    @endif
        <section class="content-header">
            <div class="row">
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.editions.index') }}">Tornei</a></li>
                    <li><a href="/admin/editions/{!! $bracket->tournament->edition->id !!}/edit">Edizione torneo</a></li>                
                    <li><a href="/admin/tournaments/{!! $bracket->tournament->id !!}/brackets">Categorie tabelloni</a></li>                
                </ol>            
            </div>
            
            <div class="row">                
                <div class="col-sm-6">             
                    <h1>
                    {!! $bracket->tournament->edition->edition_name !!} 
                    </h1>                
                    <h3>{!! $bracket->tournament->name !!} {!! $bracket->zone->name !!} {!! $bracket->category->name !!} {!! $bracket->categoryType->name !!}</h3>                                        
                </div>                                

                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-12">
                        @if( \App\Models\User::where('id', '=', \Illuminate\Support\Facades\Auth::id() )->first()->id_role == 1 )        
                            @csrf
                            <input type="hidden" name="id_bracket" value="{!! $bracket->id !!}">
                            @if($bracket->edit_mode)            
                                <button type="submit" name="btn_save_bracket" class="btn btn-success pull-right"><i class="fa fa-check"></i> Salva tabellone</button>
                            @elseif( !$bracket->edit_mode )
                                <button type="submit" name="btn_edit_bracket" class="btn btn-warning pull-right"><i class="fa fa-edit"></i> Modifica tabellone</button>                            
                            @endif        
                        @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                        @if( \App\Models\User::where('id', '=', \Illuminate\Support\Facades\Auth::id() )->first()->id_role == 1 && $bracket->edit_mode == 0)                                
                            <div class="pull-right" >
                                <br>
                                <input id="online-{!! $bracket->id !!}" type="checkbox" @if($bracket->flag_online) checked @endif>        
                                <br>
                                <label>Online</label>
                            </div>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>    

        <div class="content">    
            @if($bracket->edit_mode)            
            <div class="form-group">
                <label for="bracket_note">Note</label>
                <textarea class="form-control" name="bracket_note" id="bracket_note" rows="3">{!! $bracket->note !!}</textarea>
            </div>
            @else
            <div class="form-group">
                <label>Note</label>
                <p>{!! $bracket->note !!}</p>
            </div>                
            @endif
            
            <div class="table-responsive">
                <table class="table">                    
                    <tr>
                        @foreach($arr_phases as $fase => $num_matches)
                            <th style="color: #000; text-align: center">
                                @switch($num_matches)
                                    @case(16) Sedicesimi @break
                                    @case(8)  Ottavi     @break
                                    @case(4)  Quarti     @break
                                    @case(2)  Semifinale @break
                                    @case(1)  Finale     @break
                                    @deafault
                                        Fase {!! $fase !!}
                                        @break
                                @endswitch                                
                                @if($bracket->edit_mode)            
                                    <input type="text" name="description-{!! $fase !!}" value="{!! $phases_descriptions[$fase] !!}" class="form-control">
                                @else
                                    - {!! $phases_descriptions[$fase] !!}
                                @endif
                            </th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach($arr_phases as $fase => $num_matches)
                            <td style="padding: 20px;">                                
                                    @if(isset($arr_matches[$fase]))                                        
                                        @foreach($arr_matches[$fase] as $k => $match)     
                                        <table class="table table-striped" style="min-width: 580px;">            
                                            <tr>                                                
                                                @if(empty($match->id_club))
                                                <td colspan="3" class="text-center">Da definire</td>
                                                @else
                                                <td colspan="3" class="text-center"><i class="fa fa-clock-o"></i> {!! $match->date->format('d/m/Y') !!} Ore {!! \Carbon\Carbon::createFromFormat('H:i:s', $match->time, 'Europe/London')->format('H:i') !!} - {!! \App\Models\Club::where('id', '=', $match->id_club)->first()->name !!} </td>
                                                @endif
                                            </tr>       
                                            <tr>                                                
                                                <td style="width: 45%">
                                                    {{--
                                                    @if($bracket->edit_mode && $fase == 1)
                                                        {!! Form::select('position_'.$match->id.'L', $positions, $match->id.'L', ['class' => 'form-control']) !!}
                                                    @endif
                                                    --}}
                                                    <table class="table">     
                                                        @if(isset($match->team1))
                                                            @foreach($match->team1->players as $player)
                                                                @if($player->starter)                                                                
                                                                    <tr>
                                                                        <td class="pull-right" style="border-top: none">{!! $player->player->name !!} {!! $player->player->surname !!}</td>
                                                                        <td style="width: 30px; border-top: none">                                      
                                                                            @if(  count($player->player->metas) > 0 )
                                                                                @foreach($player->player->metas as $meta)                            
                                                                                    @if($meta->meta == 'avatar')                            
                                                                                        <img src="{!! env('APP_URL') !!}/storage/{!! $meta->meta_value !!}" class="img-circle pull-left" style="width: 40px; height: 40px;">                
                                                                                    @endif
                                                                                @endforeach
                                                                            @else                            
                                                                                <img src="https://via.placeholder.com/40?text=?" class="img-circle pull-left">                                        
                                                                            @endif                                    
                                                                        </td>
                                                                        
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                        @if($bracket->edit_mode == 1)
                                                        <select name="team1_{{$match->id}}" class="form-control">
                                                            <option value="">Seleziona squadra...</option>
                                                            @foreach($subscriptions as $subscription)
                                                                @if($match->id_team1 == $subscription->team->id) 
                                                                    <option value="{!! $subscription->team->id !!}" selected>{!! $subscription->team->name !!}</option>                                                            
                                                                @else
                                                                    <option value="{!! $subscription->team->id !!}">{!! $subscription->team->name !!}</option>                                                            
                                                                @endif                                                                
                                                            @endforeach
                                                        </select>                                                                                                                                                                        
                                                        @endif
                                                    </table>                                                                                                        
                                                </td>
                                                <td style="width: 10%; text-align: center; max-width: 100px">
                                                    vs                                                    
                                                </td>
                                                <td style="width: 45%">
                                                    {{--
                                                    @if($bracket->edit_mode && $fase == 1)
                                                        {!! Form::select('position_'.$match->id.'R', $positions, $match->id.'R', ['class' => 'form-control']) !!}
                                                    @endif
                                                    --}}
                                                    <table class="table">   
                                                    @if(isset($match->team2))                         
                                                        @foreach($match->team2->players as $player)
                                                            @if($player->starter)
                                                                <tr>
                                                                    <td style="width: 30px; border-top: none">                                      
                                                                        @if(  count($player->player->metas) > 0 )
                                                                            @foreach($player->player->metas as $meta)                            
                                                                                @if($meta->meta == 'avatar')                            
                                                                                    <img src="{!! env('APP_URL') !!}/storage/{!! $meta->meta_value !!}" class="img-circle pull-left" style="width: 40px; height: 40px;">                
                                                                                @endif
                                                                            @endforeach
                                                                        @else                            
                                                                            <img src="https://via.placeholder.com/40?text=?" class="img-circle pull-left">                                        
                                                                        @endif                                    
                                                                    </td>
                                                                    <td style="border-top: none">{!! $player->player->name !!} {!! $player->player->surname !!}</td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    
                                                    @if($bracket->edit_mode == 1)
                                                    <select name="team2_{{$match->id}}" class="form-control">
                                                        <option value="">Seleziona squadra...</option>
                                                        @foreach($subscriptions as $subscription)                                                            
                                                            @if($match->id_team2 == $subscription->team->id) 
                                                                <option value="{!! $subscription->team->id !!}" selected>{!! $subscription->team->name !!}</option>                                                            
                                                            @else
                                                                <option value="{!! $subscription->team->id !!}">{!! $subscription->team->name !!}</option>                                                            
                                                            @endif                                                                    
                                                        @endforeach
                                                    </select>
                                                    @endif
                                                    </table>                                                                                                                                                               
                                                </td>                                                                                                
                                            </tr>
                                            <tr style="background: #fff">
                                                <td class="text-right">
                                                    @if( isset($match->id) && isset($match->team1) && isset($match->team2) ) 
                                                        <a href="javascript:void(0);" onClick="vittoriaATavolino({!! $match->id !!}, {!! $match->team1->id !!}, {!! $match->team2->id !!})">Assegna vittoria a tavolino <i class="fa fa-trophy"></i> </button></a>                                                              
                                                    @endif
                                                </td>
                                                <td></td>
                                                <td>
                                                    @if( isset($match->id) && isset($match->team1) && isset($match->team2) ) 
                                                        <a href="javascript:void(0);" onClick="vittoriaATavolino({!! $match->id !!}, {!! $match->team2->id !!}, {!! $match->team1->id !!})"><i class="fa fa-trophy"></i> Assegna vittoria a tavolino</button></a>                                                              
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-center">
                                                    @php
                                                        $edition_type = 1;
                                                    @endphp
                                                    @include('admin.matches.create')
                                                </td>
                                            </tr>
                                        </table>
                                        @endforeach
                                    @endif                                
                            </td>
                        @endforeach
                    </tr>                    
                </table>
            </div>
        </div>    
    @if( \App\Models\User::where('id', '=', \Illuminate\Support\Facades\Auth::id() )->first()->id_role == 1 )        
    </form>
    @endif
    
    
    <div class="row">
        @foreach($subscriptions as $subscription)
        <div class="col-sm-3">
            <table class="table table-striped" style="background-color: #fff">
                <tr>
                    <th>{!! $subscription->team->name !!}</th>                    
                </tr>

                @foreach($subscription->team->players as $player)
                <tr>
                    <td>
                        {!! $player->player->name !!} {!! $player->player->surname !!}
                    </td>
                </tr>
                @endforeach

            </table>
        </div>
        @endforeach
    </div>

@include('admin.matches.modal_schedule')


<div id="modal-score" class="modal fade" tabindex="-1" role="dialog">            
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Torneo</h4>
            </div>
            <div class="modal-body">                    
                <form id="form-score" action="{!! route('admin.scores.store') !!}" method="post" class="rotext-center">
                    @csrf
                    <table class="table">
                    @for($set=1;$set<=5;$set++)
                    <tr>
                        <td><input id="score-team1-set-{!! $set !!}" name="score-team1-set-{!! $set !!}" type='number' style="width: 90%; margin: 0 auto; font-size: 20px" class="form-control" value="0" required /></td>
                        <td>-</td>
                        <td><input id="score-team2-set-{!! $set !!}" name="score-team2-set-{!! $set !!}" type='number' style="width: 90%; margin: 0 auto; font-size: 20px" class="form-control" value="0" required /></td>
                    </tr>
                    @endfor
                    </table>
                    <br>
                    @if($current_user->id_role == 1)                    
                    <div class="text-center">
                        <label for="note">Note</label>
                        <textarea name="note" id="note" class="form-control" rows="2"></textarea>
                    </div>
                    </br>
                    <div class="text-center">
                        <label for="a_tavolino">Risultato a tavolino</label>
                        <input type="checkbox" value="1" name="a_tavolino" id="a_tavolino">
                    </div>                
                    @endif
                    <br>                
                    <input type="hidden" id="score-id-match" name="score-id-match">
                    <input type="hidden" id="score-id-team1" name="score-id-team1">
                    <input type="hidden" id="score-id-team2" name="score-id-team2">
                                        
                    <input id="btn-ins-score" type="submit" name="insert_score" class="btn btn-success btn-block" value="INSERISCI RISULTATO">                    
                    <input id="btn-ins-score" type="submit" name="delete_score" class="btn btn-warning btn-block" value="ELIMINA RISULTATO">                    
                    <input id="btn-ins-score" type="submit" name="delete_schedule" class="btn btn-danger btn-block" value="ELIMINA PROGRAMMA E RISULTATO">
                </form>    
            </div>        
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


@endsection


@section('scripts')
<script>
$(document).ready(function(){    
    $("[id^=online-]").on('ifChanged', function(){
        var flag_online = $(this).is(':checked');
        
        var data = {    
            "_token": "{{ csrf_token() }}",        
            id_bracket : $(this).attr('id').split('-')[1],        
            flag_online: flag_online
        }

        $.ajax({
            url: '/admin/brackets/online',
            type:'post',
            data: data,
            success: function(res){  
            }
        });
    });
});
</script>

<script>

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
    var match_date = $("#modal-schedule #match_date input").val();
    var match_hours = $("#modal-schedule #match_hours input").val();
    var match_club = $("#modal-schedule #match_club").val();
    
    var data = {    
        "_token": "{{ csrf_token() }}",        
        id_match : id_match,        
        match_date: match_date,
        match_hours: match_hours,        
        match_club: match_club        
    };

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

function vittoriaATavolino(id_match, id_team_winner, id_team_loser){
    if( confirm("Assegnare la vittoria a tavolino per la squadra selezionata?") ){
        $.ajax({
            url: '/admin/score/'+id_match+'/forfait',
            data: {
                "_token": "{{ csrf_token() }}",  
                id_team_winner: id_team_winner,
                id_team_loser: id_team_loser
            },
            type: 'post',
            dataType: 'json',
            success: function(data){
                if( data.status == 'ok' ){
                    document.location.reload();
                }
            }
        });
    }
}
</script>
@endsection