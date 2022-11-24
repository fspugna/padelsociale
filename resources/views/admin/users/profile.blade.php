@extends('admin.layouts.app')

@section('css')
<style>
.twitter-typeahead, .tt-hint, .tt-input, .tt-menu { width: 100%; margin-top: 10px; }
.tt-menu.tt-open { width: 100%; margin-top: -20px; }
</style>
@endsection

@section('content')

<section class="content-header">
    <h1>
        Profilo utente
    </h1>
</section>
<div class="content">
       
    @include('adminlte-templates::common.errors')

    {!! Form::open(['route' => 'admin.update_profile', 'files' => true]) !!}     
    
    <div class="box box-primary">        

        <div class="box-body">
            <div class="row">
                                                        
                <div class="form-group col-sm-12 col-lg-6 text-center">                                                                       
                @if( $user->id_role == 1 )

                    <!-- Name Field -->
                    <div class="form-group col-sm-12">                        
                        {!! Form::label('name', 'Nome') !!}
                        {!! Form::text('name', $user->name, ['class' => 'form-control']) !!}
                    </div>

                    <!-- Cogname Field -->
                    <div class="form-group col-sm-12">                        
                        {!! Form::label('surname', 'Cognome') !!}
                        {!! Form::text('surname', $user->surname, ['class' => 'form-control']) !!}
                    </div>
                    
                    <!-- Email Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('email', 'Email') !!}
                        {!! Form::text('email', $user->email, ['class' => 'form-control']) !!}
                    </div>
                    
                    <!-- Email Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('new_password', 'Password') !!}
                        {!! Form::password('new_password', ['class' => 'form-control']) !!}
                    </div>
                    
                    <!-- Email Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('confirm_new_password', 'Conferma Password') !!}
                        {!! Form::password('confirm_new_password', ['class' => 'form-control']) !!}
                    </div>

                @endif
                </div>
                
            </div>
            
        </div>
        
    </div>
                  
    <button type="submit" name="btn_submit_profile" value="save_profile" class="btn btn-lg btn-primary">{{ trans('labels.save') }}</button>                
    
    {!! Form::close() !!}
</div>

@endsection

@section('scripts')
<script>

    var add_clubs = [];

    $(function () {
        /* BOOTSTRAP SLIDER */
        $('.span2').slider().on('change', function(){
            alert("stop");
        });  
    })


    function selectClub (id_club){    
        $("#sel_club").val(id_club);
        
        if(add_clubs.indexOf(id_club) < 0){
            add_clubs.push(id_club);
            addClub();
        }else{
            $("#sel_club").val('');
            $("#search-input").val('');    
        }
    }

    function removeClub(id_club){
        $("#tr_club_"+id_club).remove();
        $("#search-input").val('');
    }

    function addClub(){                

        var id_club = $("#sel_club").val();

        $.ajax({
            url: '/admin/club/'+id_club+'/get',
            type: 'get',
            dataType: 'json',
            success: function(data){            
                $("#sel_club").val('');
                $("#search-input").val('');

                console.log(data);            
            
                var row = '<tr id="tr_club_'+data.id+'">';                
                row += '<td>'+data.name+'<input type="hidden" name="id_name" value="'+data.id+'"></td>';
                row += '<td>'+data.address+'</td>';            
                row += '<td><input type="hidden" name="userClubs[]" value="'+data.id+'"><button type="button" class="btn btn-danger" onclick="removeClub('+data.id+')"><i class="fa fa-trash-o"></i></button></td>';        
                row += '</tr>';

                $("#tbody-clubs").append(row);            
            }
        });
    }

    $(document).ready(function($) {

        $('.user_club_id').each(function(k, val){
            add_clubs.push(parseInt($(this).val()));
        });    

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
                    return '<a href="javascript:void(0);" onClick="selectClub('+data.id+')" class="list-group-item">' + data.name + ' <br> ' + data.address + '</a>'
                }
            }
        });
    });
</script>
@endsection