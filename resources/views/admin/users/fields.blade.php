<div class="row">
    <!-- Name Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('name', 'Nome') !!}
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Name Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('surname', 'Cognome') !!}
        {!! Form::text('surname', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Email Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('email', 'Email') !!}
        {!! Form::email('email', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Email Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('mobile_phone', 'Cellulare') !!}
        {!! Form::text('mobile_phone', null, ['class' => 'form-control']) !!}
    </div>
    
    @if( $user->id_role == 2 )
    <!-- Gender Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('gender', trans('labels.gender') ) !!}
        {!! Form::select('gender', ['m' => 'Maschio', 'f' => 'Femmina'], null, ['class' => 'form-control']) !!}
    </div>
    @else
        <input type="hidden" name="gender" value="m">
    @endif

    <!-- Id Role Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('id_role', trans('labels.role') ) !!}
        {!! Form::select('id_role', $roles, null, ['class' => 'form-control']) !!}
    </div>

    <!-- Status Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('status', trans('labels.status') ) !!}
        {!! Form::select('status', $stati, null, ['class' => 'form-control']) !!}
    </div>

    @if( $user->id_role == 2 )
    <!-- City Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('id_city', 'Città' ) !!}
        {!! Form::select('id_city', $cities, null, ['class' => 'form-control']) !!}
    </div>
    @endif

    @if( $user->id_role == 2 )
    <!-- Club Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('id_club', 'Circolo preferito' ) !!}
        {!! Form::select('id_club', [null=>'Seleziona circolo']+$clubs, null, ['class' => 'form-control']) !!}
    </div>
    @endif

</div>

@if( $user->id_role == 3 )
<br><br>

<div class="row">
    <div class="col-sm-12">
        <h3>Dati Circolo</h3>
    </div>

    <!-- Email Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('club_name', 'Nome Circolo') !!}
        {!! Form::text('club_name', (isset($user_club) ? $user_club->name : ''), ['class' => 'form-control']) !!}
    </div>

    @if( $user->id_role == 3 )
    <!-- City Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('id_city', 'Città' ) !!}
        {!! Form::select('id_city', $cities, null, ['class' => 'form-control']) !!}
    </div>
    @endif

    <div class="form-group col-sm-6">
        {!! Form::label('club_address', 'Indirizzo Circolo') !!}
        {!! Form::text('club_address', (isset($user_club) ? $user_club->address : ''), ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('club_phone', 'Telefono') !!}
        {!! Form::text('club_phone', (isset($user_club) ? $user_club->phone : ''), ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('club_mobile_phone', 'Cellulare') !!}
        {!! Form::text('club_mobile_phone', (isset($user_club) ? $user_club->mobile_phone : ''), ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-12">
        {!! Form::label('club_description', 'Descrizione') !!}
        {!! Form::textarea('club_description', (isset($user_club) ? $user_club->description : ''), ['class' => 'form-control', 'id' => 'club_description']) !!}
    </div>
</div>
@endif

<br><br>

<div class="row">
    <!-- Change Password -->
    <div class="form-group col-sm-6">
        {!! Form::label('new_password', 'Nuova password' ) !!}
        {!! Form::password('new_password', ['class' => 'form-control']) !!}
    </div>

    <!-- Change Password -->
    <div class="form-group col-sm-6">
        {!! Form::label('confirm_password', 'Conferma password' ) !!}
        {!! Form::password('confirm_password', ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Salva', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.users.index') !!}" class="btn btn-default">{!! trans('labels.cancel'); !!}</a>
</div>
