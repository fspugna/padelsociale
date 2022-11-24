
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">    
        
        <form class="typeahead" role="search">
            
            <input type="search" name="q" id="search-input" class="form-control search-input" placeholder="Aggiungi giocatore" autocomplete="off">
        
            <div id="team-player-add" class="input-group-btn">
            
                <input type="hidden" id="id_team" name="id_team" value="{!! $team->id !!}">
                <input type="hidden" id="id_player" name="id_player">                                
            
            </div>
        </form>            
        
    </div>
</div>

<div class="table-responsive">
    
    @if( $team->flag_change == 0 )
    <h3><span class="label label-success">La squadra non ha ancora effettuato sostituzioni</span></h3>
    @elseif( $team->flag_change == 1 )
    <h3><span class="label label-danger">La squadra ha effettuato una sostituzione</span></h3>
    @endif    
    
    <table class="table table-striped">
        <thead>
            <td></td>
            <td>Giocatore</td>
            <td>Email</td>
            <td>Telefono</td>
            <td>Titolare</td>
        </thead>
        <tbody id="tbody-team-players">
            @foreach( $team->players as $player)
            <tr id="player_{!! $player->id_player !!}">
                <td style="width: 55px;"><img src="https://via.placeholder.com/50?text=?" class="img-circle"></td>
                <td class="text-left">{!! $player->player->name !!} {!! $player->player->surname !!}</td>
                <td>{!! $player->player->email !!}</td>
                <td>{!! $player->player->mobile_phone !!}</td>
                <td>
                
                    @if($player->starter) 
                        <input type="checkbox" name="starter_{{ $player->player->id }}" checked>                        
                    @else 
                        <input type="checkbox" name="starter_{{ $player->player->id }}">
                    @endif
                
                </td>
                <td>
                    @if( $player->player->id != Auth::id() )                                                
                    <button type="button" class="btn btn-danger" onClick="removeTeamPlayer({!! $player->id !!})"><i class="fa fa-trash"></i></button>
                    @endif
                </td>
            </tr>        
            @endforeach
        </tbody>
    </table>
           
</div>

