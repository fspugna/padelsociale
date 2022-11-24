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
                                    @foreach($arr_matches[$fase] as $k => $macroMatch)
                                    {{--
                                    <table class="table table-striped" style="min-width: 580px;">
                                        <tr>
                                            @if(empty($macroMatch->id_club))
                                            <td colspan="3" class="text-center">Da definire</td>
                                            @else
                                            <td colspan="3" class="text-center"><i class="fa fa-clock-o"></i> {!! $macroMatch->date->format('d/m/Y') !!} Ore {!! \Carbon\Carbon::createFromFormat('H:i:s', $macroMatch->time, 'Europe/London')->format('H:i') !!} - {!! \App\Models\Club::where('id', '=', $macroMatch->id_club)->first()->name !!} </td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="width: 45%;">
                                                <table class="table">
                                                    @if($bracket->edit_mode == 1)
                                                    <select name="macro_team1_{{$macroMatch->id}}" class="form-control">
                                                        <option value="">Seleziona squadra...</option>
                                                        @foreach($subscriptions as $subscription)
                                                            @if($macroMatch->id_team1 == $subscription->team->id)
                                                                <option value="{!! $subscription->team->id !!}" selected>{!! $subscription->team->name !!}</option>
                                                            @else
                                                                <option value="{!! $subscription->team->id !!}">{!! $subscription->team->name !!}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    @else
                                                        @if( !empty($macroMatch->team1) )
                                                        {!! $macroMatch->team1->name !!}
                                                        @endif
                                                    @endif
                                                </table>
                                            </td>
                                            <td style="width: 10%; text-align: center; max-width: 100px">
                                                vs
                                                @if( !empty($macroMatch->team1) && !empty($macroMatch->team2) )
                                                <br>
                                                <a href="javascript:void(0);" onClick="showSubmatches({!! $macroMatch->id !!})">Incontri</a>
                                                @endif
                                            </td>
                                            <td style="width: 45%">
                                                <table class="table">
                                                @if($bracket->edit_mode == 1)
                                                <select name="macro_team2_{{$macroMatch->id}}" class="form-control">
                                                    <option value="">Seleziona squadra...</option>
                                                    @foreach($subscriptions as $subscription)
                                                        @if($macroMatch->id_team2 == $subscription->team->id)
                                                            <option value="{!! $subscription->team->id !!}" selected>{!! $subscription->team->name !!}</option>
                                                        @else
                                                            <option value="{!! $subscription->team->id !!}">{!! $subscription->team->name !!}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @else
                                                        @if( !empty($macroMatch->team2) )
                                                        {!! $macroMatch->team2->name !!}
                                                        @endif
                                                @endif
                                                </table>
                                            </td>
                                        </tr>
                                        <tr id="submatches_{!! $macroMatch->id !!}" style="display: none">
                                            <td colspan="3" class="text-center" style="background: yellow">
                                                @php
                                                    $edit_mode = $bracket->edit_mode;
                                                    $edition_type = $bracket->tournament->edition->edition_type;
                                                @endphp
                                                @if( !empty($macroMatch->id_team1) && !empty($macroMatch->id_team2) )
                                                    @include('admin.matches.submatches')
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                    --}}

                                    @if($bracket->edit_mode)
                                        @include('admin.brackets.macro_match')
                                    @else
                                        @include('admin.brackets.show_macro_match')
                                    @endif

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
        <div class="col-sm-3" style="height: 300px; overflow: auto;">
            <table class="table table-striped" style="background-color: #fff; margin: 15px;">
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

@include('admin.matches.modal_schedule_macro')


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

function showSubmatches(id_match){
    $("#submatches_"+id_match).toggle();
}

$("#btn_schedule_macro").click(function(){


  var id_match    = $("#modal-schedule-macro #id_macro_match").val();
  var match_date  = $("#modal-schedule-macro #match_date input").val();
  var match_hours = $("#modal-schedule-macro #match_hours input").val();
  var match_club  = $("#modal-schedule-macro #match_club").val();

  var data = {
      "_token": "{{ csrf_token() }}",
      id_macro_match : id_match,
      match_date: match_date,
      match_hours: match_hours,
      match_club: match_club
  };
  console.log("scheduleFormMacro", data);

  $.ajax({
      url: '/admin/macro_matches/schedule',
      type:'post',
      data: data,
      dataType: 'json',
      success: function(res){
          console.log("schedule res: " , res);
          if(res.status == 'ok'){
              $("#btn-schedule-"+id_match).remove();
              $("#modal-schedule-macro").modal('hide');
              location.reload();
          }else if(res.status == 'error'){
              alert(res.msg);
          }
      }
  });

});

$("#btn_del_schedule_macro").click(function(){

  var id_match    = $("#modal-schedule-macro #id_macro_match").val();

  var data = {
      "_token": "{{ csrf_token() }}",
      id_macro_match : id_match,
  };
  console.log("delScheduleFormMacro", data);

  $.ajax({
      url: '/admin/macro_matches/del_schedule',
      type:'post',
      data: data,
      dataType: 'json',
      success: function(res){
          console.log("schedule res: " , res);
          if(res.status == 'ok'){
              $("#btn-schedule-"+id_match).remove();
              $("#modal-schedule-macro").modal('hide');
              location.reload();
          }else if(res.status == 'error'){
              alert(res.msg);
          }
      }
  });

});

function openSubmatches(id_macro_match){
  var current_url = window.location.href;
  console.log(current_url);
  var new_url = current_url.split('?')[0] + '?id_macro_match='+id_macro_match;
  history.replaceState('', 'La Disfida di Padel', new_url);
  $("#panel_submatches_"+id_macro_match).toggle();

  document.getElementById("panel_submatches_"+id_macro_match).scrollIntoView();

  $(".panel_submatches").each(function(k, val){
      if( $(this).attr('id') != 'panel_submatches_'+id_macro_match ){
          $(this).hide();
      }
  });

  $(".table-macro-match").toggle();
  $(".table-macro-match-"+id_macro_match).toggle();

}

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


function vittoriaATavolinoMacro(id_macro_match, id_team_winner, id_team_loser){
  if( confirm("Assegnare la vittoria a tavolino per la squadra selezionata?") ){
      $.ajax({
          url: '/admin/score/'+id_macro_match+'/forfait_macro',
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

function noVittoriaATavolinoMacro(id_macro_match){
  if( confirm("Eliminare la vittoria a tavolino?") ){
      $.ajax({
          url: '/admin/score/'+id_macro_match+'/remove_forfait_macro',
          data: {
              "_token": "{{ csrf_token() }}"
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

function incontroNullo(id_macro_match){
  if(confirm("Annullare l'incontro con risultato 0-0?")){
      $.ajax({
          url: '/admin/macro_match/'+id_macro_match+'/makeNull',
          data:{
              "_token": "{{ csrf_token() }}",
          },
          type: 'POST',
          dataType: 'json',
          success: function(data){
              if( data.status == 'OK' ){
                  window.location.reload();
              }
          }
      });
  }
}

function deleteMacroMatch(id_macro_match){

  if(confirm("Eliminare l'incontro selezionato?")){
      $.ajax({
          url: '/admin/macro_match/'+id_macro_match+'/delete',
          data:{
              "_token": "{{ csrf_token() }}",
          },
          type: 'POST',
          dataType: 'json',
          success: function(data){
              if( data.status == 'ok' ){
                  window.location.reload();
              }
          }
      });
  }
}

function openSchedule(id_macro_match){
  $("#modal-schedule-macro #id_macro_match").val(id_macro_match);
  $("#modal-schedule-macro").modal("show");
}
</script>
@endsection
