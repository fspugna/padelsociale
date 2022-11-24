@csrf

<!-- Id Tournament Type Field -->
<div class="form-group col-sm-12">
    {!! Form::label('name', 'Nome') !!}
    {!! Form::text('name', isset($tournament) ? $tournament->name : null, ['class' => 'form-control']) !!}
</div>


<!-- Id Tournament Type Field -->
<div class="form-group col-sm-12">
    {!! Form::label('id_tournament_type', trans('labels.tournament_type')) !!}
    {!! Form::select('id_tournament_type', $tournament_types, isset($tournament) ? $tournament->id_tournament_type : null, ['class' => 'form-control']) !!}
</div>

 <!-- Date Start Field -->
 <div class="form-group col-sm-6">
    {{ Form::label('date_start', 'Data inizio') }}
    {{-- Form::text('daterange', null, ['id' => 'daterange', 'class' => 'form-control', 'readonly', 'required' => true ]) --}}                        
    {{ Form::date('date_start', date('d/m/Y') , ['id' => 'date_start', 'class' => 'form-control']) }}    
</div>

<div class="form-group col-sm-6">
    {{ Form::label('date_end', 'Data fine') }}    
    {{ Form::date('date_end',  date('d/m/Y') , ['id' => 'date_end', 'class' => 'form-control']) }}              
</div>

<!-- Registration Deadline Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('registration_deadline_date', trans('labels.registration_deadline_date') ) !!}
    {!! Form::date('registration_deadline_date', null, ['class' => 'form-control','id'=>'registration_deadline_date']) !!}
</div>                    
