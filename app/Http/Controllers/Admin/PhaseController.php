<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Models\Phase;
use App\Models\PhaseTeams;
use App\Models\Match;
use App\Models\Bracket;
use App\Models\Matchcode;
use App\Models\Ranking;
use App\Models\Tournament;
use App\Models\Subscription;
use App\Models\MacroMatch;
use App\Notifications\JoinBracket;

class PhaseController extends Controller
{         
    public function generate($id_phase, $num_matches){        
        $phase = Phase::find($id_phase);        
        $bracket = $phase->bracket; //Bracket::find($phase->id_bracket);                        
        //$teams = [];

        Match::where('matchcode', '=', $phase->matchcode)->delete();
     
        /*
        foreach($phase->teams as $phaseTeam):
            $teams[] = $phaseTeam->id_team;                                    
        endforeach;            
         * 
         */        

        //dd($teams);
        //$num_matches = count($teams) / 2;

        $flagNextMatchcode = true;
        $nextMatchcode = null;
        $arr_phases  = [];        
        $arr_matches = [];
        $fase = 1;

        /** Preparo tutti i match della prima fase */
        for($i=1;$i<=$num_matches;$i++):            
            /*
            $iteam1 = rand( 0 , count($teams)-1 );
            $id_team1 = $teams[$iteam1];
            unset($teams[$iteam1]);
            $teams = array_values($teams);
            $iteam2 = rand( 0 , count($teams)-1 );
            $id_team2 = $teams[$iteam2];
            unset($teams[$iteam2]);
            $teams = array_values($teams);
            */
            
            $match = new Match;
            $match->matchcode = $phase->matchcode;
            //$match->id_team1 = $id_team1;
            //$match->id_team2 = $id_team2;
            $match->save();                        

            if($flagNextMatchcode){
                $nextMatchcode = new Matchcode;
                $nextMatchcode->id_ref = $match->id;
                $nextMatchcode->ref_type = 'match';
                $nextMatchcode->save();
                $flagNextMatchcode = false;
            }else{
                $flagNextMatchcode = true;
            }

            $match->next_matchcode = $nextMatchcode->id;
            $match->save();            

        endfor;        

        if( $num_matches == 1 ):
            $bracket->generated = 1;
            $bracket->save();
            return;
        endif;

        $arr_phases[$fase] = $num_matches;
        $arr_matches[$fase] = Match::where('matchcode', '=', $phase->matchcode)->get();                        

        $fase++;        

        // Creo tutte le successive fasi del tabellone                 
        $start = Match::where('matchcode', '=', $phase->matchcode)->count();        

        for($i=($start/2); $i>=1; $i=$i/2 ):                        
        
            $iPhase = new Phase;
            $iPhase->id_bracket = $bracket->id;
            $iPhase->name = $fase;
            $iPhase->save();                    
        
            /** Creo il matchcode per la nuova fase */
            $matchcode = new Matchcode;
            $matchcode->id_ref = $iPhase->id;
            $matchcode->ref_type = 'phase';
            $matchcode->save();

            /** Creo la fase successiva */            
            $iPhase->matchcode = $matchcode->id;
            $iPhase->save();                    

            $fase++;

        endfor; 

        $last_fase = --$fase;        

        // Creo i match per ogni fase
        // Recupero tutte le fasi del tabellone
        $phases = Phase::where('id_bracket', '=', $bracket->id)->get();               
        $date_start = $bracket->tournament->date_start;
        $date_end = $bracket->tournament->date_end;

        $diff = $date_start->diffInDays($date_end);
        $days_per_phase = ( $diff / count($phases) ) - 1;

        $phase_start = $date_start;
        $phase_end = $date_start->copy()->addDays($days_per_phase);             

        $phases_descriptions = [];

        // Scorro tutte le fasi e per ogni fase recupero gli incontri
        foreach($phases as $phase):                        

            if($phase_start->format('d/m/Y') !== $phase_end->format('d/m/Y')):
                $phase->description = 'Dal ' . $phase_start->format('d/m/Y') . ' al ' . $phase_end->format('d/m/Y');
            else:
                $phase->description = $phase_start->format('d/m/Y');
            endif;

            $phase->save();

            $phases_descriptions[$phase->name] = $phase->description;
            $phase_start = $phase_end->copy()->addDays(1);        
            $phase_end = $phase_start->copy()->addDays($days_per_phase); 

            if($phase_end > $date_end):
                $phase_end = $date_end;
            endif;

            $matches = Match::where('matchcode', '=', $phase->matchcode)->get();

            if($phase->name > 1 && $phase->name !== $last_fase):                

                $flagNextMatchcode = true;                

                // Creo il next_matchcode
                foreach($matches as $match):                

                    if($flagNextMatchcode){
                        $nextMatchcode = new Matchcode;
                        $nextMatchcode->id_ref = $match->id;
                        $nextMatchcode->ref_type = 'match';
                        $nextMatchcode->save();        
                        $flagNextMatchcode = false;
                    }else{
                        $flagNextMatchcode = true;
                    }                                            
                    
                    $match->next_matchcode = $nextMatchcode->id;
                    $match->save();

                endforeach;                

            endif;

            foreach($matches as $match):                
            
                // Cerco gli incontri che hanno prev_matchcode = next_matchcode
                $nextMatch = Match::where('prev_matchcode', '=', $match->next_matchcode)->first();

                // Per creare l'incontro recupero prima la fase successiva
                $nextPhase = Phase::where('id_bracket', '=', $phase->id_bracket)
                                    ->where('name', '=', ($phase->name+1))
                                    ->first();  

                // Se non trovo l'incontro successivo lo creo
                if(!$nextMatch ):                                                                                  
                    $nextMatch = new Match;
                    $nextMatch->matchcode = $nextPhase->matchcode;
                    $nextMatch->prev_matchcode = $match->next_matchcode;
                    $nextMatch->save();

                    $matchcode = new Matchcode;
                    $matchcode->id_ref = $nextMatch->id;
                    $matchcode->ref_type = 'match';
                    $matchcode->save();
                
                    /** Per la finale non inserisco il next matchcode */
                    if( $nextPhase->name < $last_fase ):
                        $nextMatch->next_matchcode = $matchcode->id ;
                        $nextMatch->save();                        
                    endif;            
                endif;

            endforeach;
        
            $arr_matches[$phase->name] = $matches;

        endforeach;           
        
        $bracket->generated = 1;
        $bracket->save();

        $my_matches = [];
        $allScores = [];
        $my_team = null;
        $clubs = [];                

        return;
                
    }

    

    public function generate_macro($id_phase, $num_matches){        

        $phase = Phase::find($id_phase);        
        $bracket = $phase->bracket; //Bracket::find($phase->id_bracket);                        
        MacroMatch::where('matchcode', '=', $phase->matchcode)->delete();

        //$num_matches = count($teams) / 2;

        $flagNextMatchcode = true;
        $nextMatchcode = null;

        $arr_phases  = [];        
        $arr_matches = [];
        $fase = 1;

        /** Preparo tutti i match della prima fase */
        for($i=1;$i<=$num_matches;$i++):                        

            $macroMatch = new MacroMatch;
            $macroMatch->matchcode = $phase->matchcode;
            //$match->id_team1 = $id_team1;
            //$match->id_team2 = $id_team2;
            $macroMatch->save();                        

            if($flagNextMatchcode){
                $nextMatchcode = new Matchcode;
                $nextMatchcode->id_ref = $macroMatch->id;
                $nextMatchcode->ref_type = 'macro_match';
                $nextMatchcode->save();
                $flagNextMatchcode = false;
            }else{
                $flagNextMatchcode = true;
            }
    
            $macroMatch->next_matchcode = $nextMatchcode->id;
            $macroMatch->save();            

        endfor;        

        $arr_phases[$fase] = $num_matches;
        $arr_matches[$fase] = MacroMatch::where('matchcode', '=', $phase->matchcode)->get();                
    
        $fase++;        

        // Creo tutte le successive fasi del tabellone                 
        $start = MacroMatch::where('matchcode', '=', $phase->matchcode)->count();        

        for($i=($start/2); $i>=1; $i=$i/2 ):                        
        
            $iPhase = new Phase;
            $iPhase->id_bracket = $bracket->id;
            $iPhase->name = $fase;
            $iPhase->save();                    
        
            /** Creo il matchcode per la nuova fase */
            $matchcode = new Matchcode;
            $matchcode->id_ref = $iPhase->id;
            $matchcode->ref_type = 'phase';
            $matchcode->save();

            /** Creo la fase successiva */            
            $iPhase->matchcode = $matchcode->id;
            $iPhase->save();                    

            $fase++;

        endfor; 

        $last_fase = --$fase;        

        // Creo i match per ogni fase
        // Recupero tutte le fasi del tabellone
        $phases = Phase::where('id_bracket', '=', $bracket->id)->get();           
        
        $date_start = $bracket->tournament->date_start;

        $date_end = $bracket->tournament->date_end;

        $diff = $date_start->diffInDays($date_end);

        $days_per_phase = ( $diff / count($phases) ) - 1;

        $phase_start = $date_start;

        $phase_end = $date_start->copy()->addDays($days_per_phase);             

        $phases_descriptions = [];

        // Scorro tutte le fasi e per ogni fase recupero gli incontri
        foreach($phases as $phase):                        
            if($phase_start->format('d/m/Y') !== $phase_end->format('d/m/Y')):
                $phase->description = 'Dal ' . $phase_start->format('d/m/Y') . ' al ' . $phase_end->format('d/m/Y');
            else:
                $phase->description = $phase_start->format('d/m/Y');
            endif;

            $phase->save();

            $phases_descriptions[$phase->name] = $phase->description;

            $phase_start = $phase_end->copy()->addDays(1);        

            $phase_end = $phase_start->copy()->addDays($days_per_phase);             

            if($phase_end > $date_end):

                $phase_end = $date_end;

            endif;

            $macroMatches = MacroMatch::where('matchcode', '=', $phase->matchcode)->get();

            if($phase->name > 1 && $phase->name !== $last_fase):                

                $flagNextMatchcode = true;
            
                // Creo il next_matchcode

                foreach($macroMatches as $macroMatch):                                
                    if($flagNextMatchcode){
                        $nextMatchcode = new Matchcode;
                        $nextMatchcode->id_ref = $macroMatch->id;
                        $nextMatchcode->ref_type = 'macro_match';
                        $nextMatchcode->save();
        
                        $flagNextMatchcode = false;

                    }else{

                        $flagNextMatchcode = true;

                    }                                            
                
                    $macroMatch->next_matchcode = $nextMatchcode->id;
                    $macroMatch->save();
            
                endforeach;                

            endif;
    
            foreach($macroMatches as $macroMatch):                
            
                // Cerco gli incontri che hanno prev_matchcode = next_matchcode
                $nextMatch = MacroMatch::where('prev_matchcode', '=', $macroMatch->next_matchcode)->first();

                // Per creare l'incontro recupero prima la fase successiva
                $nextPhase = Phase::where('id_bracket', '=', $phase->id_bracket)
                                    ->where('name', '=', ($phase->name+1))
                                    ->first();  

                // Se non trovo l'incontro successivo lo creo
                if(!$nextMatch ):                                                                                  
                    $nextMatch = new MacroMatch;
                    $nextMatch->matchcode = $nextPhase->matchcode;
                    $nextMatch->prev_matchcode = $macroMatch->next_matchcode;
                    $nextMatch->save();

                    $matchcode = new Matchcode;
                    $matchcode->id_ref = $nextMatch->id;
                    $matchcode->ref_type = 'macro_match';
                    $matchcode->save();
                    
                    /** Per la finale non inserisco il next matchcode */
                    if( $nextPhase->name < $last_fase ):
                        $nextMatch->next_matchcode = $matchcode->id ;
                        $nextMatch->save();                        
                    endif;
    
                endif;

            endforeach;
    
            $arr_matches[$phase->name] = $macroMatches;

        endforeach;           

        $bracket->generated = 1;
        $bracket->save();                        
        $my_matches = [];
        $allScores = [];
        $my_team = null;
        $clubs = [];                
        return;
                
    }
}