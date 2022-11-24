@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Zone
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.zones.store']) !!}

                        @include('admin.zones.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>

var add_clubs = [];

$(document).ready(function(){    
    
    // Set the Options for "Bloodhound" suggestion engine
    var engine = new Bloodhound({
        remote: {
            url: '/admin/club/search?q=%QUERY%',
            wildcard: '%QUERY%'
        },
        datumTokenizer: Bloodhound.tokenizers.whitespace('q'),
        queryTokenizer: Bloodhound.tokenizers.whitespace
    });

    $(".search-input").change(function(){
        $("#club_id").val('');
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
        name: 'clubsList',

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
                return '<a href="#" onClick="selectClub('+data.id+')" class="list-group-item">' + data.name + '<br>' + data.address + '</a>'
            }
        }
    });    
});

function selectClub (id_club){    
    $("#sel_club").val(id_club);

    console.log(add_clubs, id_club, add_clubs.indexOf(parseInt(id_club)));

    if( add_clubs.indexOf(parseInt(id_club)) >= 0 ){
        $("#sel_club").val('');
        $("#search-input").val('');
        return;
    }

    add_clubs.push(id_club);
    addZoneClub();
}

function removeClub(id_club){
    $("#tr_club_"+id_club).remove();
    $("#search-input").val('');
}

function addZoneClub(){            

    var id_club = $("#sel_club").val();    

    $.ajax({
        url: '/admin/club/'+id_club+'/get',
        type: 'get',
        dataType: 'json',
        success: function(data){            
            
            console.log(data);                    

            var row = '<tr id="tr_club_'+data.id+'">';
            //row += '<td><img src="{!! env('APP_URL') !!}/storage/'+avatar+'" style="width: 30px; height: 30px" class="img-circle"></td>';
            row += '<td>'+data.name+'<input type="hidden" name="id_club[]" value="'+data.id+'"></td>';            
            row += '<td><button type="button" class="btn btn-danger" onclick="removeClub('+data.id+')"><i class="fa fa-trash-o"></i></button></td>';        
            row += '</tr>';

            $("#tbody-zone-clubs").append(row);            

            $("#sel_club").val('');
            $("#search-input").val('');

        }
    });

}
</script>
@endsection