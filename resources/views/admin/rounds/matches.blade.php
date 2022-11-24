@extends('admin.layouts.app')

@section('content')

    <section class="content-header">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.editions.index') }}"><i class="fa fa-dashboard"></i> Tornei</a></li>
                <li><a href="{{ route('admin.subscriptions', ['id_tournament' => $round->group->division->tournament->id]) }}">Iscrizioni</a></li>
                <li><a href="{{ route('admin.divisions.index', ['id_division' => $round->group->division->id]) }}">Gironi</a></li>
                <li><a href="/admin/rounds/{!! $round->group->id !!}/index">Calendario</a></li>
            </ol>
        </div>
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <h1>Giornata {!! $round->name !!}</h1>
                <h3>{!! $round->group->division->category->name !!} {!! $round->group->division->categoryType->name !!}</h3>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <a href="/admin/rounds/{!! $round->id !!}/macro_matches/add" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Aggiungi Incontro Squadre</a>
            </div>
        </div>
    </section>

    <div class="content">

        <form action="/admin/groups/{!! $round->group->id !!}/macro_calendar/save" method="POST">
            @csrf

            <div class="row">
                @foreach($round->macro_matches as $k => $macroMatch)
                    @include('admin.rounds.macro_match')
                @endforeach
            </div>

            @if( $round->macro_matches->count() )
            <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-check"></i> Salva giornata</button>
            @endif

        </form>

    </div>

    @include('admin.matches.modal_schedule_macro')


    <div id="modal-score" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Risultato incontro</h4>
                </div>
                <div class="modal-body">
                    <form id="form-score" action="{!! route('admin.scores.store') !!}" method="post" class="rotext-center">
                        @csrf
                        <table class="table">
                        @for($set=1;$set<=5;$set++)
                        <tr>
                            <td><input id="score-team1-set-{!! $set !!}" name="score-team1-set-{!! $set !!}" type='number' style="width: 90%; margin: 0 auto; font-size: 20px" class="form-control" value=""  /></td>
                            <td style="text-align: center"><small>Set<br>{!! $set !!}</small></td>
                            <td><input id="score-team2-set-{!! $set !!}" name="score-team2-set-{!! $set !!}" type='number' style="width: 90%; margin: 0 auto; font-size: 20px" class="form-control" value=""  /></td>
                        </tr>
                        @endfor
                        </table>
                        <br>
                        @if($current_user->id_role == 1)
                        <div class="text-center">
                            <label for="note">Note</label>
                            <textarea name="note" id="note" class="form-control" rows="2"></textarea>
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

$(document).ready(function () {

    //initialize swiper when document ready
    var mySwiper = new Swiper ('.swiper-container',{
    // Optional parameters

    loop: true,

    // If we need pagination
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
        renderBullet: function (index, className) {
          return '<span class="' + className + '">' + (index + 1) + '</span>';
        },
      },

  });

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

});

function openSubmatches(id_macro_match){
    var current_url = window.location.href;
    console.log(current_url);
    var new_url = current_url.split('?')[0] + '?id_macro_match='+id_macro_match;
    history.replaceState('', 'Padel Sociale', new_url);
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

