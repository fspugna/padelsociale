<h1>Si è registrato un nuovo 
@if($user->id_role == 2) giocatore
@elseif($user->id_role == 3) circolo
@endif
</h1>
<p>
Nome: {!! $user->name !!}
Email: Email: {!! $user->email !!}
</p>
<p>
Per attivare l'utente <a href="{!! env('APP_URL') !!}/admin/users">clicca qui</a>
</p>
