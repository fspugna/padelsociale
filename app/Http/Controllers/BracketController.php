<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Bracket;
use App\Models\Tournament;
use App\Models\Zone;
use App\Models\Group;
use App\Models\Phase;
use App\Models\Match;
use App\Models\MacroMatch;
use App\Models\MatchPlayer;

class BracketController extends Controller
{
    public function show($id_bracket){

        $bracket = Bracket::where('id', '=', $id_bracket)->first();

        if($bracket->flag_online == 1):
            $phases = Phase::where('id_bracket', '=', $id_bracket)->get();

            return view('single-categoria')->with('phases', $phases);
        endif;
    }


    public function showFull($id_tournament, $id_zone, $id_category_type, $id_category, $id_bracket){

        $tournament = Tournament::where('id', '=', $id_tournament)->first();

        if( in_array($tournament->edition->edition_type, [0,1]) ):
            return $this->showFullDouble($id_tournament, $id_zone, $id_category_type, $id_category, $id_bracket);
        elseif( $tournament->edition->edition_type == 2 ):
            return $this->showFullMacro($id_tournament, $id_zone, $id_category_type, $id_category, $id_bracket);
        endif;

    }

    public function showFullDouble($id_tournament, $id_zone, $id_category_type, $id_category, $id_bracket){

        $tournament = Tournament::where('id', '=', $id_tournament)->first();

        $tournament['srcImgFeaturedBig'] = asset('/storage/'.$tournament->edition->logo);
        $tournament['srcImgFeaturedBigx2'] = asset('/storage/'.$tournament->edition->logo);

        $bracket = Bracket::where('id', '=', $id_bracket)->first();

        $phases = Phase::where('id_bracket', '=', $bracket->id)->get();

        $matches = [];

        foreach($phases as $phase):
            $matches[$phase->name] [] = Match::where('matchcode', '=', $phase->matchcode)->get();
        endforeach;


        $scores = [];
        $rounds = [];
        $subscriptions = [];
        $matchPlayers = [];

        if($phases):
            foreach($phases as $iphase):

                if( $iphase->name == 1 ):

                    $fase_iniziale = count($iphase->matches);

                endif;

                if( $iphase->matches ):
                    foreach($iphase->matches as $match):

                        $players_team1 = MatchPlayer::where('id_match', '=', $match->id)
                                                    ->where('id_team', '=', $match->id_team1)
                                                    ->get();

                        foreach($players_team1 as $matchPlayer){
                            $matchPlayers[$match->id]['team1'][] = $matchPlayer->player;
                        }



                        $players_team2 = MatchPlayer::where('id_match', '=', $match->id)
                                                    ->where('id_team', '=', $match->id_team2)
                                                    ->get();

                        foreach($players_team2 as $matchPlayer){
                            $matchPlayers[$match->id]['team2'][] = $matchPlayer->player;
                        }



                        foreach($match->scores as $score):

                            if($score->id_team == $match->id_team1):
                                $scores[$iphase->id][$match->id][$score->set]['team1'] = $score->points;
                            else:
                                $scores[$iphase->id][$match->id][$score->set]['team2'] = $score->points;
                            endif;

                        endforeach;
                    endforeach;
                endif;
            endforeach;
        endif;

        $page_tabellone = '';

        switch($fase_iniziale){
            case 16: $page_tabellone = 'page-tabellone-sedicesimi'; break;
            case 8: $page_tabellone = 'page-tabellone-ottavi'; break;
            case 4: $page_tabellone = 'page-tabellone-quarti'; break;
            case 2: $page_tabellone = 'page-tabellone-semifinali'; break;
        }

        return view($page_tabellone)->with('bracket', $bracket)
                                     ->with('phases', $phases)
                                     ->with('matches', $matches)
                                     ->with('scores', $scores)
                                     ->with('tournament', $tournament)
                                     ->with('match_players', $matchPlayers)
                                    ;
    }

    public function showFullMacro($id_tournament, $id_zone, $id_category_type, $id_category, $id_bracket){

        $tournament = Tournament::where('id', '=', $id_tournament)->first();

        $tournament['srcImgFeaturedBig'] = asset('/storage/'.$tournament->edition->logo);
        $tournament['srcImgFeaturedBigx2'] = asset('/storage/'.$tournament->edition->logo);

        $bracket = Bracket::where('id', '=', $id_bracket)->first();

        $phases = Phase::where('id_bracket', '=', $bracket->id)->get();

        $matches = [];

        foreach($phases as $phase):
            $matches[$phase->name] [] = MacroMatch::where('matchcode', '=', $phase->matchcode)->get();
        endforeach;


        $scores = [];
        $rounds = [];

        $matchPlayers = [];

        if($phases):
            foreach($phases as $iphase):

                if( $iphase->name == 1 ):

                    $fase_iniziale = count($iphase->macro_matches);

                endif;

                if( $iphase->matches ):
                    foreach($iphase->macro_matches as $macroMatch):

                        $players_team1 = MatchPlayer::where('id_match', '=', $macroMatch->id)
                                                    ->where('id_team', '=', $macroMatch->id_team1)
                                                    ->get();

                        foreach($players_team1 as $matchPlayer){
                            $matchPlayers[$macroMatch->id]['team1'][] = $matchPlayer->player;
                        }



                        $players_team2 = MatchPlayer::where('id_match', '=', $macroMatch->id)
                                                    ->where('id_team', '=', $macroMatch->id_team2)
                                                    ->get();

                        foreach($players_team2 as $matchPlayer){
                            $matchPlayers[$macroMatch->id]['team2'][] = $matchPlayer->player;
                        }


                        /*
                        foreach($macroMatch->scores as $score):

                            if($score->id_team == $match->id_team1):
                                $scores[$iphase->id][$match->id][$score->set]['team1'] = $score->points;
                            else:
                                $scores[$iphase->id][$match->id][$score->set]['team2'] = $score->points;
                            endif;

                        endforeach;
                         *
                         */
                    endforeach;
                endif;
            endforeach;
        endif;

        $page_tabellone = '';

        switch($fase_iniziale){
            case 16: $page_tabellone = 'page-tabellone-macro-sedicesimi'; break;
            case 8: $page_tabellone = 'page-tabellone-macro-ottavi'; break;
            case 4: $page_tabellone = 'page-tabellone-macro-quarti'; break;
            case 2: $page_tabellone = 'page-tabellone-macro-semifinali'; break;
        }

        return view($page_tabellone)->with('bracket', $bracket)
                                     ->with('phases', $phases)
                                     ->with('matches', $matches)
                                     ->with('scores', $scores)
                                     ->with('tournament', $tournament)
                                     ->with('match_players', $matchPlayers)
                                    ;
    }
}
