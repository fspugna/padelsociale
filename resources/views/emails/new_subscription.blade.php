<h1>Nuova iscrizione</h3>

<h3>Torneo: {!! $tournament->name !!}</h3>
<ul>
    <li>Zona: {!! $zone->city->name !!} - {!! $zone->name !!}</li>
    <li>Tipologia: {!! $category_type->name !!}</li>
</ul>

<h3>Squadra</h3>
<ul>
    @foreach($players as $player)
    <li>{!! $player->player->name !!} {!! $player->player->surname !!} - {!! $player->player->email !!}</li>
    @endforeach
</ul>