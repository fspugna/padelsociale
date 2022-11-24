@if( isset($my_matches[$match->id]) && !empty(isset($my_matches[$match->id])) )                    
    <p>               
        @if( $edition_type < 2 )
            @if( empty($my_matches[$match->id]->id_club) && !empty($my_matches[$match->id]->id_team1) && !empty($my_matches[$match->id]->id_team2))      
            <button id="btn-schedule-{!! $match->id !!}" type="button" class="btn btn-warning btn-sm" title="Programma incontro" onClick="openScheduleMatch({!! $match->id !!});"><i class="fa fa-clock-o"></i> Programma incontro</button>
            @elseif( !empty($match->id_club) && !count($match->scores) )                    
            @endif
        @endif
        
        @if( isset($match->id) && isset($match->team1) && isset($match->team2) )
        <button id="btn-score-{!! $match->id !!}" type="button" class="btn btn-primary btn-sm" title="Inserisci risultato" onClick="openScoreMatch({!! $match->id !!}, {!! $match->team1->id !!}, {!! $match->team2->id !!});"><i class="fa fa-edit"></i> Risultato</button>
        @endif
        <div id="div-score-{!! $match->id !!}" class="hidden"></div>    
    
        @if( !empty($match->id_club) && count($match->scores) )                            
            <div id="div-score-{!! $match->id !!}" class="hidden"></div>            
        @endif
    </p>
@endif

@if( isset($allScores[$match->id]) )
    <p>Risultato @if( $match->a_tavolino == 1) ( a tavolino ) @endif</p>
    @if( isset($match->note) )
    <p>{!! $match->note !!}</p>
    @endif
    <p>
    @foreach( $allScores[$match->id]['scores'] as $k => $score)                        
        @if( isset($score[$match->id_team1]) && isset($score[$match->id_team2]) )
        <span style="font-size: 18px">
            <label class="label label-success">                
                {!! $score[$match->id_team1] !!}  - {!! $score[$match->id_team2] !!}                
            </label>
        </span>
        @endif
    @endforeach    
    </p>
@endif

{{--
<div class="row text-center">
    <div class="col-sm-12">
        @if( !empty($match->id_club) && count($match->scores) )       
            <button id="btn-score-{!! $match->id !!}" type="button" class="btn btn-warning btn-sm" title="Modifica risultato" onClick="editScoreMatch({!! $match->id !!}, {!! $match->team1->id !!}, {!! $match->team2->id !!});"><i class="fa fa-clock-o"></i> Modifica risultato</button>
        @endif
        <a href="/admin/images/match/{!! $match->id !!}" class="btn btn-sm btn-primary"><i class="fa fa-image"></i> Multimedia</a>
    </div>
</div>
--}}