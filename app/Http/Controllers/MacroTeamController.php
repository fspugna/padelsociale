<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\MacroTeam;
use App\Models\MacroSubscription;
use App\Models\Division;
use App\Models\Group;
use App\Models\Round;
use App\Models\MacroMatch;
use App\Models\Classification;
use App\Models\MatchPlayer;

class MacroTeamController extends Controller
{
    public function show($id_macro_team){
        $macroTeam = MacroTeam::find($id_macro_team);

        $subscription = MacroSubscription::where('id_team', '=', $id_macro_team)->first();

        $division = Division::where('id_tournament', $subscription->id_tournament)
                            ->where('id_zone', $subscription->id_zone)
                            ->where('id_category_type', $subscription->id_category_type)
                            ->where('id_category', $subscription->id_category)
                            ->first();

        $group = Group::where('id_division', '=', $division->id)
                        ->whereHas('macro_teams', function($teams) use($id_macro_team) {
                            $teams->where('id_team', $id_macro_team);
                        })
                        ->first();

        if(!empty($goup)){
            $rounds = Round::where('id_group', $group->id)->get();
        }else{
            $rounds = null;
        }

        $classifica = []; // giocatore -> punti

        $classifica_dett = []; // giocatore -> dettaglio classifica
        $classification_points = []; // numero di squadre per punteggio
        $classification_gdiff = []; // giocatore -> differenza games vinti
        $classification_players = []; // id_giocatore -> obj giocatore

        foreach($macroTeam->players as $player):
            $classification_players[$player->id_player] = $player->player;

            $classifica[$player->id_player] = 0;

            $classifica_dett[$player->id_player] = [];
            $classifica_dett[$player->id_player]['played'] = 0;
            $classifica_dett[$player->id_player]['won'] = 0;
            $classifica_dett[$player->id_player]['lost'] = 0;
            $classifica_dett[$player->id_player]['draws'] = 0;
            $classifica_dett[$player->id_player]['sw'] = 0;
            $classifica_dett[$player->id_player]['sl'] = 0;
            $classifica_dett[$player->id_player]['gw'] = 0;
            $classifica_dett[$player->id_player]['gl'] = 0;

            $classification_gdiff[$player->id_player] = 0;
        endforeach;

        $macro_matches = [];
        $giornate = [];
        $match_players = [];
        $scores = [];
        $allMacroScores = [];

        if($rounds){
            foreach($rounds as $round):

                $macroMatches = MacroMatch::where('matchcode', $round->matchcode)->get();

                $giornate[$round->id] = $round;

                foreach($macroMatches as $macroMatch):

                    $side = null;

                    if( $macroMatch->id_team1 == $id_macro_team || $macroMatch->id_team2 == $id_macro_team ):

                        foreach($macroMatch->subMatches as $match):
                            $matchPlayers = MatchPlayer::where('id_team', '=', $match->id_team1)->get();
                            foreach($matchPlayers as $player):
                                $match_players[$match->id]['team1'][] = $player->player;
                            endforeach;

                            $matchPlayers = MatchPlayer::where('id_team', '=', $match->id_team2)->get();
                            foreach($matchPlayers as $player):
                                $match_players[$match->id]['team2'][] = $player->player;
                            endforeach;

                            foreach($match->scores as $score){
                                if($score->id_team == $match->id_team1):
                                    $scores[$match->id][$score->set]['team1'] = $score->points;
                                else:
                                    $scores[$match->id][$score->set]['team2'] = $score->points;
                                endif;
                            }
                        endforeach;

                        if( $macroMatch->id_team1 == $id_macro_team ):
                            $side = 'team1';
                        elseif( $macroMatch->id_team2 == $id_macro_team ):
                            $side = 'team2';
                        endif;

                        if( !isset($macro_matches[$round->id]) ):
                            $macro_matches [$round->id] = [];
                        endif;
                        $macro_matches [$round->id][] = $macroMatch;

                        $allMacroScores[$macroMatch->id] = [];
                        $allMacroScores[$macroMatch->id]['team1'] = null;
                        $allMacroScores[$macroMatch->id]['team2'] = null;


                        foreach($macroMatch->macro_scores as $macroScore):
                            if($macroScore->id_team == $macroMatch->id_team1):
                                $allMacroScores[$macroMatch->id]['team1'] += $macroScore->points;
                            elseif($macroScore->id_team == $macroMatch->id_team2):
                                $allMacroScores[$macroMatch->id]['team2'] += $macroScore->points;
                            endif;
                        endforeach;

                        foreach($macroMatch->subMatches as $match):

                                if( $side == 'team1' ):

                                    $matchPlayers = MatchPlayer::where('id_team', '=', $match->id_team1)->get();

                                    $classification_team1 = Classification::where('id_team', $match->id_team1)->get();
                                    foreach($classification_team1 as $classification):

                                        foreach($matchPlayers as $player):

                                            if( isset($classifica[$player->id_player]) ):
                                                $classifica[$player->id_player] += ( $classification->games_won - $classification->games_lost);

                                                $classifica_dett[$player->id_player]['played'] += $classification->played;
                                                $classifica_dett[$player->id_player]['won'] += $classification->won;
                                                $classifica_dett[$player->id_player]['lost'] += $classification->lost;
                                                $classifica_dett[$player->id_player]['draws'] += $classification->draws;
                                                $classifica_dett[$player->id_player]['sw'] += $classification->set_won;
                                                $classifica_dett[$player->id_player]['sl'] += $classification->set_lost;
                                                $classifica_dett[$player->id_player]['gw'] += $classification->games_won;
                                                $classifica_dett[$player->id_player]['gl'] += $classification->games_lost;

                                                $classification_gdiff[$player->id_player] += ( $classification->games_won - $classification->games_lost );
                                            endif;
                                        endforeach;
                                    endforeach;

                                elseif( $side == 'team2' ):

                                    $matchPlayers = MatchPlayer::where('id_team', '=', $match->id_team2)->get();

                                    $classification_team2 = Classification::where('id_team', $match->id_team2)->get();
                                    foreach($classification_team2 as $classification):

                                        foreach($matchPlayers as $player):

                                            if( isset($classifica[$player->id_player]) ):

                                                $classifica[$player->id_player] += ( $classification->games_won - $classification->games_lost);

                                                $classifica_dett[$player->id_player]['played'] += $classification->played;
                                                $classifica_dett[$player->id_player]['won'] += $classification->won;
                                                $classifica_dett[$player->id_player]['lost'] += $classification->lost;
                                                $classifica_dett[$player->id_player]['draws'] += $classification->draws;
                                                $classifica_dett[$player->id_player]['sw'] += $classification->set_won;
                                                $classifica_dett[$player->id_player]['sl'] += $classification->set_lost;
                                                $classifica_dett[$player->id_player]['gw'] += $classification->games_won;
                                                $classifica_dett[$player->id_player]['gl'] += $classification->games_lost;

                                                $classification_gdiff[$player->id_player] += ( $classification->games_won - $classification->games_lost );
                                            endif;
                                        endforeach;
                                    endforeach;
                                endif;

                        endforeach;
                    endif;
                endforeach;
            endforeach;
        }


        /* Calcolo quante squadre ci sono per ogni punteggio */
        foreach($classifica as $c):
            if( !isset($classification_points[$c]) ):
                $classification_points[$c] = 0;
            endif;
            $classification_points[$c]++;
        endforeach;

        /* Elenco dei distinti punteggi in classifica */
        $classifica_punti = array_unique($classifica);
        rsort($classifica_punti);

        $giocatori_per_punteggio = [];
        foreach($classifica as $id_player => $punti):
            $giocatori_per_punteggio [$punti] [] = $id_player;
        endforeach;

        $classifica_finale = [];
        $gdiff = [];
        foreach($classifica_punti as $cp):
            foreach($giocatori_per_punteggio[$cp] as $giocatore):
                /* Prendo tutte le squadre del punteggio corrente */
                $gdiff[$giocatore] = $classification_gdiff[$giocatore];
            endforeach;
            arsort($gdiff);

            foreach($gdiff as $id_giocatore => $g):
                $classifica_finale[$id_giocatore]['punti'] = $classifica[$id_giocatore];
                $classifica_finale[$id_giocatore]['dett'] =  $classifica_dett[$id_giocatore];
                $classifica_finale[$id_giocatore]['giocatore'] =  $classification_players[$id_giocatore];
            endforeach;
        endforeach;

        //dd($classifica_finale);
        //dd($classifica, $classifica_dett, $classification_points, $classification_gdiff, $classification_players, $giocatori_per_punteggio );

        return view('page-macro-team')
                ->with('macro_team', $macroTeam)
                ->with('classifica', $classifica_finale)
                ->with('macro_matches', $macro_matches)
                ->with('giornate', $giornate)
                ->with('macro_scores', $allMacroScores)
                ->with('scores', $scores)
                ->with('match_players', $match_players)
                ;
    }
}

