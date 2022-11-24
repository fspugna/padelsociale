<table class="table" data-macro-match="{{ $macroMatch->id }}">
    <tr style="background-color: lightgreen">                                    
        <td colspan="3" class="text-center" style="padding: 0; border: 0; font-weight: 600">
            @if( !empty($macroMatch->id_club) && !empty($macroMatch->date) && !empty($macroMatch->time) )                            
            {!! $macroMatch->date->format('d/m/Y') !!} Ore {!! \Carbon\Carbon::createFromFormat('H:i:s', $macroMatch->time, 'Europe/London')->format('H:i') !!} - {!! \App\Models\Club::where('id', '=', $macroMatch->id_club)->first()->name !!}
            @else
            <i>Programmazione da definire</i>
            @endif
        </td>
    </tr>
    <tr>
        <td class="text-right" style="width: 40%">
            @if( $macroMatch->team1 )
                {{ $macroMatch->team1->name }}
            @endif
        </td>
        <td class="text-center" style="width: 20%">
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
        <td class="text-left" style="width: 40%">
            @if( $macroMatch->team2 )
                {{ $macroMatch->team2->name }}
            @endif
        </td>
    </tr>
</table>

