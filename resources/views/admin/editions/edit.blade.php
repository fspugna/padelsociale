@extends('admin.layouts.app')

@section('css')
<!-- include summernote css/js -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
@endsection


@section('content')
    <section class="content-header">
        <h1>
            {!! trans('labels.edition') !!}
        </h1>
   </section>
   <div class="content">
        @include('adminlte-templates::common.errors')
        {!! Form::model($edition, ['route' => ['admin.editions.update', $edition->id], 'method' => 'patch', 'files' => true]) !!}


            @include('admin.editions.fields')

        {!! Form::close() !!}
   </div>
@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>

var formchanged = false;

$(document).ready(function(){

    $( "#selected-zones" ).sortable();

    window.onbeforeunload = confirmExit;

    $('#edition_description').summernote({height: 300});
    $('#edition_rules').summernote({height: 300});
    $('#edition_zone_rules').summernote({height: 300});
    $('#edition_awards').summernote({height: 300});
    $('#edition_zones_and_clubs').summernote({height: 300});

    $('form *').change(function(){
        formchanged = true;
    });

    $("#id_tournament_type").change(function(){
        if($(this).val() == 1){
            $("#div_tournament_ref").addClass("hidden");
        }else{
            $("#div_tournament_ref").removeClass("hidden");
        }
    });

    /*
    $('#registration_deadline_date').datepicker({
        language: 'it',
        autoclose: true
    });
    */

    $('form').on('submit', function(){
        formchanged = false;
    });

});

confirmExit = function () {
    if( formchanged )
        return true;
}


addZone = function() {
    var zone_id = $('#zones-select option:selected').val();
    var zone_name = $('#zones-select option:selected').text();

    if(zone_name !== ''){
        var row = '<tr id="tr_zone_'+zone_id+'"><td width="80%"><span id="zone_name">'+zone_name+'</span><input type="hidden" name="zones[]" value="'+zone_id+'"></td><td width="10%"><button type="button" class="btn btn-sm btn-danger" onClick=removeZone('+zone_id+')><i class="fa fa-trash"></i></button></td></tr>';

        $("#selected-zones").append(row);
        $('#zones-select option[value='+zone_id+']').remove();

        formchanged = true;
    }
}

removeZone = function(id_zone){
    var zone_name = $('#tr_zone_'+id_zone+' #zone_name').text();
    var o = new Option(zone_name, id_zone);
    $(o).html(zone_name);
    $("#zones-select").append(o);
    $('#tr_zone_'+id_zone).remove();

    formchanged = true;
}


addCategory = function() {
    var category_id = $('#categories-select option:selected').val();
    var category_name = $('#categories-select option:selected').text();

    if(category_name !== ''){
        var row = '<tr id="tr_category_'+category_id+'"><td width="80%"><span id="category_name">'+category_name+'</span><input type="hidden" name="categories[]" value="'+category_id+'"></td><td width="10%"><button type="button" class="btn btn-sm btn-danger" onClick=removeCategory('+category_id+')><i class="fa fa-trash"></i></button></td></tr>';

        $("#selected-categories").append(row);
        $('#categories-select option[value='+category_id+']').remove();

        formchanged = true;
    }
}

removeCategory = function(id_category){
    var category_name = $('#tr_category_'+id_category+' #category_name').text();
    var o = new Option(category_name, id_category);
    $(o).html(category_name);
    $("#categories-select").append(o);
    $('#tr_category_'+id_category).remove();

    formchanged = true;
}



addCategoryType = function() {
    var category_type_id = $('#category-types-select option:selected').val();
    var category_type_name = $('#category-types-select option:selected').text();

    if(category_type_name !== ''){
        var row = '<tr id="tr_category_type_'+category_type_id+'"><td width="80%"><span id="category_type_name">'+category_type_name+'</span><input type="hidden" name="category_types[]" value="'+category_type_id+'"></td><td width="10%"><button type="button" class="btn btn-sm btn-danger" onClick=removeCategoryType('+category_type_id+')><i class="fa fa-trash"></i></button></td></tr>';

        $("#selected-category-types").append(row);
        $('#category-types-select option[value='+category_type_id+']').remove();

        formchanged = true;
    }
}

removeCategoryType = function(id_category_type){
    var category_type_name = $('#tr_category_type_'+id_category_type+' #category_type_name').text();
    var o = new Option(category_type_name, id_category_type);
    $(o).html(category_type_name);
    $("#category-types-select").append(o);
    $('#tr_category_type_'+id_category_type).remove();

    formchanged = true;
}

addTournament = function(id_tournament){
    $("#modal-tournament #id_tournament").val('');
    $("#modal-tournament #id_tournament_type").val(1);
    $("#modal-tournament #name").val('');

    //var period = $('#daterange').val().split(' - ');
    $("#modal-tournament #date_start").val('');
    $("#modal-tournament #date_end").val('');
    $("#modal-tournament #registration_deadline_date").val('');
    $("#modal-tournament #description").val('');

    $("#modal-tournament").modal('show');
}

saveTournament = function(){

    var data = {
        "_token": "{{ csrf_token() }}",
        id_edition : $("#modal-tournament #id_edition").val(),
        id_tournament : $("#modal-tournament #id_tournament").val(),
        //id_tournament_ref : $("#modal-tournament #id_tournament_ref").val(),
        id_tournament_type : $("#modal-tournament #id_tournament_type").val(),
        name : $("#modal-tournament #name").val(),
        date_start : $("#modal-tournament #date_start").val(),
        date_end : $("#modal-tournament #date_end").val(),
        registration_deadline_date : $("#modal-tournament #registration_deadline_date").val()
        //,description : $("#modal-tournament #description").val(),
    }

    //console.log("save tournament data", data);

    var url = '/admin/tournaments/store';
    if( data.id_tournament != '' ){
        url = '/admin/tournaments/'+data.id_tournament+'/update';
    }

    $.ajax({
        url: url,
        type:'post',
        data: data,
        success: function(res){

            /*
            if( url.indexOf('/update') >= 0 ){

                var row = '<td width="30%">'+res.name+'<input type="hidden" name="tournaments[]" value="'+res.id+'"></td>';
                row += '<td width="15%">'+res.tournament_type['tournament_type']+'</td>';
                row += '<td width="15%">'+res.date_start+'</td>';
                row += '<td width="15%">'+res.date_end+'</td>';
                row += '<td width="15%">'+res.registration_deadline_date+'</td>';
                row += '<td width="15%">'
                row += '<button type="button" class="btn btn-sm btn-danger pull-right" onclick="deleteTournament('+res.id+')"><i class="fa fa-trash"></i></button>';
                row += '<button type="button" class="btn btn-sm btn-default pull-right" onclick="editTournament('+res.id+')"><i class="fa fa-edit"></i></button>';
                row += '</td>';
                $('#tr_tournament_'+res.id).html(row);

            }else{

                var row = '<tr id="tr_tournament_'+res.id+'">';
                row += '<td width="30%">'+res.name+'<input type="hidden" name="tournaments[]" value="'+res.id+'"></td>';
                row += '<td width="15%">'+res.tournament_type['tournament_type']+'</td>';
                row += '<td width="15%">'+res.date_start+'</td>';
                row += '<td width="15%">'+res.date_end+'</td>';
                row += '<td width="15%">'+res.registration_deadline_date+'</td>';
                row += '<td width="15%">'
                row += '<button type="button" class="btn btn-sm btn-danger pull-right" onclick="deleteTournament('+res.id+')"><i class="fa fa-trash"></i></button>';
                row += '<button type="button" class="btn btn-sm btn-default pull-right" onclick="editTournament('+res.id+')"><i class="fa fa-edit"></i></button>';
                row += '</td>';
                row += '</tr>';
                $("#selected-tournaments").append(row);

            }
            */

            $("#modal-tournament").modal('hide');
            formchanged = false;
            url = '{!! env('APP_URL') !!}/admin/editions/'+data.id_edition+'/edit';
            document.location.href = url;

        },
        error( xhr, ajaxOptions, thrownError ){
            var msg = '';
            var errors = JSON.parse(xhr.responseText).errors;

            console.log("xhr", xhr);
            console.log("Errors", errors);

            if( errors ){
                for(var i=0; i < Object.keys( errors ).length;i++){
                    switch( Object.keys( errors )[i] ){
                        case 'date_start': msg += '\n Data Inizio non valorizzata'; break;
                        case 'date_end': msg += '\n Data Fine non valorizzata'; break;
                    }

                };
                alert(msg);
            }
        }
    })
}

editTournament = function( id_tournament ){
    var url = '/admin/tournaments/'+id_tournament+'/edit';

    $.ajax({
        url: url,
        type:'get',
        dataType: 'json',
        success: function( res ){

            console.log("res", res);

            $("#modal-tournament #id_tournament").val(id_tournament);
            $("#modal-tournament #id_tournament_type").val(res.tournament.id_tournament_type);
            $("#modal-tournament #name").val(res.tournament.name);
            $("#modal-tournament #date_start").val(res.tournament.date_start);
            $("#modal-tournament #date_end").val(res.tournament.date_end);
            $("#modal-tournament #registration_deadline_date").val(res.tournament.registration_deadline_date);
            $("#modal-tournament #description").val(res.tournament.description);

            $("#modal-tournament").modal('show');

        }
    });
}

deleteTournament = function(id_tournament){
    var url = '/admin/tournaments/'+id_tournament+'/delete';
    if( confirm('Sei sicuro?') ){
        $.ajax({
            data: {
                "_token": "{{ csrf_token() }}",
            },
            url: url,
            type:'DELETE',
            success: function( res ){
                if(res === 'OK'){
                    document.location.reload();
                }
            }
        });
    }
}

generateTournament = function(id_tournament){
    if( confirm('Procedere con la generazione del torneo?') ){

        $("#generateTournamentBtn").html('attendi...');
        $("#generateTournamentBtn").attr('disabled', true);

        var url = '/admin/tournaments/generate';

        $.ajax({
            data: {
                "_token": "{{ csrf_token() }}",
                id_tournament: id_tournament
            },
            url: url,
            type:'POST',
            dataType: 'json',
            success: function( res ){
                if(res.error !== undefined){
                    alert(res.error);
                }else if(res.success !== undefined){
                    $("#generateTournamentBtn").remove();
                    alert(res.success);
                    document.location.reload();
                }


            }
        });
    }
}
</script>
@endsection
