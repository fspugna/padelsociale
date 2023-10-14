@extends('admin.layouts.app')

@section('css')
<style>
    .swiper-container{
        padding: 10px 0;
    }
    .swiper-container-horizontal>.swiper-pagination-bullets{
        bottom: 4px;
    }

    .swiper-pagination {
        bottom: 95%;
    }

    .swiper-pagination-bullet {
      width: 20px;
      height: 20px;
      text-align: center;
      line-height: 20px;
      font-size: 12px;
      color:#000;
      opacity: 1;
      background: rgba(0,0,0,0.2);
    }
    .swiper-pagination-bullet-active {
      color:#fff;
      background: #007aff;
    }

</style>
@endsection

@section('content')
    <section class="content-header">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.editions.index') }}"><i class="fa fa-dashboard"></i> Tornei</a></li>
                <li><a href="{{ route('admin.subscriptions', ['id_tournament' => $rounds[0]->group->division->id_tournament]) }}">Iscrizioni</a></li>
                <li><a href="{{ route('admin.divisions.index', ['id_division' => $rounds[0]->group->id_division]) }}">Gironi</a></li>
            </ol>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <h3>Calendario Gruppo {{ $rounds[0]->group->name }} - {!! $rounds[0]->group->division->category->name !!} {!! $rounds[0]->group->division->categoryType->name !!}</h3>
            </div>
        </div>
    </section>

    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <a href="/admin/groups/{!! $rounds[0]->group->id !!}/editRounds" class="btn btn-warning btn-sm"><i class="fa fa-calendar"></i>  Modifica giornate</a>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
            @include('admin.rounds.table')
            </div>
        </div>
    </div>
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

const PITCH = "pitch_";
$("[name^="+PITCH+"]").on('change', function(){
    let name = $(this).attr('name');
    let match_id = name.substr(PITCH.length)
    let pitch = $(this).val();

    var data = {
        "_token": "{{ csrf_token() }}",
        pitch: pitch
    }

    $.ajax({
        url: '/admin/matches/'+match_id+'/pitch',
        type:'post',
        data: data,
        success: function(res){
        }
    });
});

</script>
@endsection
