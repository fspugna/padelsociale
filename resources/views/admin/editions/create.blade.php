@extends('admin.layouts.app')

@section('css')
<!-- include summernote css/js -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
@endsection


@section('content')
    <section class="content-header">
        <h1>
            Nuova Edizione
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        {!! Form::model($edition, ['route' => ['admin.editions.store', $edition->id], 'method' => 'post', 'files' => true]) !!}              
                
            @include('admin.editions.fields')

        {!! Form::close() !!}
                    
    </div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>

<script>

$(document).ready(function(){   
    /*
    $('#registration_deadline_date').datepicker({
        language: 'it',
        autoclose: true
    });    
    */
    $('#edition_description').summernote({height: 300});
    $('#edition_rules').summernote({height: 300});
    $('#edition_zone_rules').summernote({height: 300});
    $('#edition_awards').summernote({height: 300});
    $('#edition_zones_and_clubs').summernote({height: 300});
});


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
</script>
@endsection
