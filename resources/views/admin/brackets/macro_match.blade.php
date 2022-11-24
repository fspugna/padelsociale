<table class="table table-macro-match table-macro-match-{!! $macroMatch->id !!}">
    <tr style="background-color: lightgreen">                                    
        <td colspan="3" class="text-center" style="padding: 0; border: 0; font-weight: 600">
            @if( !empty($macroMatch->id_club) && !empty($macroMatch->date) && !empty($macroMatch->time) )                            
            {!! $macroMatch->date->format('d/m/Y') !!} Ore {!! \Carbon\Carbon::createFromFormat('H:i:s', $macroMatch->time, 'Europe/London')->format('H:i') !!} - {!! \App\Models\Club::where('id', '=', $macroMatch->id_club)->first()->name !!}
            @else
            <i>Programmazione da definire</i>
            @endif
        </td>
    </tr>

    <tr style="background-color: lightgreen">                                    
        <td  style="border-top: 0;">                                    
            <label for="id_team1_{!! $macroMatch->id !!}">Squadra</label>
            <select name="id_team1_{!! $macroMatch->id !!}"  class="form-control">
                <option value="">Seleziona squadra</option>                
                @foreach($subscriptions as $subscription)
                    @if($macroMatch->id_team1 == $subscription->team->id) 
                        <option value="{!! $subscription->team->id !!}" selected>{!! $subscription->team->name !!}</option>                                                            
                    @else
                        <option value="{!! $subscription->team->id !!}">{!! $subscription->team->name !!}</option>                                                            
                    @endif                                                                
                @endforeach
            </select>                                                                        
        </td>
        <td class="text-center"  style="border-top: 0; vertical-align: middle" rowspan="3">
            <span style="font-weight: 800">VS</span>
            <br>
            <div class="btn-group" role="group" aria-label="...">
                <a type="button" class="btn btn-sm btn-danger" href="javascript:void(0);" onclick="deleteMacroMatch({!! $macroMatch->id !!})" alt="Elimina incontro" title="Elimina incontro"><i class="fa fa-trash"></i></a>                                    
                <a type="button" class="btn btn-sm btn-primary" href="javascript:void(0);" onClick="incontroNullo({!! $macroMatch->id !!})" alt="Incontro nullo" title="Incontro nullo"><i class="fa fa-close"></i></a>
                <a type="button" class="btn btn-sm btn-warning" href="javascript:void(0);" onClick="openSchedule({!! $macroMatch->id !!})" alt="Programma incontro" title="Programma incontro"><i class="fa fa-clock-o"></i></a>
                <a type="button" class="btn btn-sm btn-success" href="javascript:void(0);" onClick="openSubmatches({!! $macroMatch->id !!})" alt="Incontri giocatori" title="Incontri giocatori"><i class="fa fa-calendar"></i></a>                    
            </div>
            <br><br>
            @if( isset($allMacroScores[$macroMatch->id][$macroMatch->id_team1]) && isset($allMacroScores[$macroMatch->id][$macroMatch->id_team2]) )
                <label>Risultato @if($macroMatch->a_tavolino == 1) (<a href="javascript:void(0);" title="No risultato a tavolino" alt="No risultato a tavolino" onClick="noVittoriaATavolinoMacro({!! $macroMatch->id !!});"><i class="fa fa-close" style="color: red"></i></a> A tavolino) @endif</label>
                <h3 style="margin-top: 5px"><label class="label label-success">
                {!! $allMacroScores[$macroMatch->id][$macroMatch->id_team1] !!} - {!! $allMacroScores[$macroMatch->id][$macroMatch->id_team2] !!}
                </label></h3>
            @else
                <label>Da giocare</label><br>    
                <label class="labe"></label> {{--Per avere gli stessi spazi--}}
            @endif  
        </td>
        <td style="border-top: 0;">
            <label for="id_team2_{!! $macroMatch->id !!}">Squadra</label>
            <select name="id_team2_{!! $macroMatch->id !!}"  class="form-control">
                <option value="">Seleziona squadra</option>
                @foreach($subscriptions as $subscription)
                    @if($macroMatch->id_team2 == $subscription->team->id) 
                        <option value="{!! $subscription->team->id !!}" selected>{!! $subscription->team->name !!}</option>                                                            
                    @else
                        <option value="{!! $subscription->team->id !!}">{!! $subscription->team->name !!}</option>                                                            
                    @endif                                                                
                @endforeach
            </select>                                                                                                                                                
        </td>
    </tr>    
    @if( isset($macroMatch->team1) && isset($macroMatch->team2) )
    <tr style="background-color: lightgreen">                                    
        <td  style="border-top: 0; padding-top: 0; margin-top: 0;">
            <label for="jolly_team1_{!! $macroMatch->id !!}">Giocatore Jolly</label>
            <select name="jolly_team1_{!! $macroMatch->id !!}"  class="form-control">
                <option value="">Seleziona giocatore jolly</option>
                @foreach( $macroMatch->team1->players as $matchPlayer )                                                                               
                <option value="{!! $matchPlayer->id_player !!}" 
                    @if($macroMatch->jolly_team1 == $matchPlayer->id_player) 
                    selected
                    @endif
                    >{!! $matchPlayer->player->name !!} {!! $matchPlayer->player->surname !!}</option>
                @endforeach
            </select>
        </td>

        <td style="border-top: 0; padding-top: 0; margin-top: 0;">
            <label for="jolly_team2_{!! $macroMatch->id !!}">Giocatore Jolly</label>
            <select name="jolly_team2_{!! $macroMatch->id !!}"  class="form-control">
                <option value="">Seleziona giocatore jolly</option>
                @foreach( $macroMatch->team2->players as $matchPlayer )                                                                               
                <option value="{!! $matchPlayer->id_player !!}" 
                    @if($macroMatch->jolly_team2 == $matchPlayer->id_player) 
                    selected
                    @endif
                    >{!! $matchPlayer->player->name !!} {!! $matchPlayer->player->surname !!}</option>
                @endforeach
            </select>                                                                                                                  
        </td>
    </tr>
    @else
    <tr style="background-color: lightgreen"><td colspan="3" style="border-top: 0; padding-top: 0; margin-top: 0;"></td></tr>
    @endif
    <tr style="background-color: lightgreen;">
        <td class="text-right"  style="border-top: 0; ">
            @if( isset($macroMatch->id) && isset($macroMatch->id_team1) && isset($macroMatch->id_team2) )
            <a href="javascript:void(0);" onClick="vittoriaATavolinoMacro({!! $macroMatch->id !!}, {!! $macroMatch->id_team1 !!}, {!! $macroMatch->id_team2 !!})">Assegna vittoria a tavolino <i class="fa fa-trophy"></i></button></a>
            @endif 
        </td>

        <td class="text-left"  style="border-top: 0;">
            @if( isset($macroMatch->id) && isset($macroMatch->id_team1) && isset($macroMatch->id_team2) )
            <a href="javascript:void(0);" onClick="vittoriaATavolinoMacro({!! $macroMatch->id !!}, {!! $macroMatch->id_team2 !!}, {!! $macroMatch->id_team1 !!})"><i class="fa fa-trophy"></i> Assegna vittoria a tavolino</button></a>
            @endif 
        </td>
    </tr>
    <tr style="background-color: lightgreen">
        <td colspan="3"  class="text-center"  style="border-top: 0; border-bottom: 4px solid #f4f4f4">
            <div class="form-group">
                <label>Note</label>
                <textarea rows="3" class="form-control" id="note_macro_match_{!! $macroMatch->id !!}" name="note_macro_match_{!! $macroMatch->id !!}">{!! $macroMatch->note !!}</textarea>
            </div>                                    
        </td>
    </tr>
    @if( $sel_macro_match != $macroMatch->id )     
    <tr id="panel_submatches_{!! $macroMatch->id !!}" class="panel_submatches" style="background-color: yellow;  display: none">
    @else    
    <tr id="panel_submatches_{!! $macroMatch->id !!}" style="background-color: yellow;">
    @endif
        <td colspan="3" style="background: yellow; border-bottom: 4px solid #f4f4f4">                                       
            @php
                $edit_mode = 1;
                $edition_type = $bracket->tournament->edition->edition_type;
            @endphp                                    
            @if( !empty($macroMatch->id_team1) && !empty($macroMatch->id_team2) )
                @include('admin.matches.submatches')
            @endif
        </td>
    </tr>
</table>