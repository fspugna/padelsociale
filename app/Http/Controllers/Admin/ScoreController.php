<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateScoreRequest;
use App\Http\Requests\UpdateScoreRequest;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

use Carbon\Carbon;

use App\Models\Score;
use App\Models\Classification;
use App\Models\Match;
use App\Models\MatchPlayer;
use App\Models\Matchcode;
use App\Models\Round;
use App\Models\Group;
use App\Models\Ranking;
use App\Models\Phase;
use App\Models\Team;
use App\Models\TeamPlayer;
use App\Models\User;

use App\Models\MacroMatch;
use App\Models\MacroClassification;
use App\Models\MacroScore;


class ScoreController extends AppBaseController
{



    /**
     * Store a newly created Score in storage.
     *
     * @param CreateScoreRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $id_match = $input['score-id-match'];
        $match = Match::find($id_match);
        $macroMatch = null;
        if( !empty($match->id_macro_match) ):

            $macroMatch = MacroMatch::where('id', '=', $match->id_macro_match)->first();

            /* Rimuovo dal risultato totale della squadra il punteggio del match che sto cancellando */
            MacroScore::where('id_submatch', '=', $match->id)->delete();
            /***********************************************************/

            MacroClassification::where('id_match', '=', $match->id_macro_match)
                                ->where('id_submatch', '=', $match->id)
                                ->delete();
        endif;

        Score::where('id_match', '=', $id_match)->delete();
        // Ranking::where('id_match', '=', $id_match)->delete();
        Classification::where('id_match', '=', $id_match)->delete();
        MatchPlayer::where('id_match', '=', $id_match)->delete();

        if( isset($input['delete_schedule']) ){

            $match->id_user = null;
            $match->id_club = null;
            $match->date = null;
            $match->time = null;
            $match->a_tavolino = 0;
            $match->save();

            if( isset($input['ajax']) && $input['ajax'] == 1 ){
                return response()->json(array('status' => 'ok'));
            }else{
                return back();
            }
        }

        if( isset($input['delete_score']) ){

            $match->id_user = null;
            $match->a_tavolino = 0;
            $match->save();

            if( isset($input['ajax']) && $input['ajax'] == 1 ){
                return response()->json(array('status' => 'ok', 'msg' => 'Risultato cancellato'));
            }else{
                return back();
            }
        }


        $match->id_user = Auth::id(); //Utente che ha salvato il risultato
        $match->a_tavolino = 0;       //Da qui non è mai un risultato a tavolino

        if(isset($input['note'])):
            $match->note = $input['note'];
        else:
            $match->note = '';
        endif;

        $match->save();


        $set_won_t1 = 0;
        $set_won_t2 = 0;

        $games_won_t1 = 0;
        $games_won_t2 = 0;

        $set_played = 0;


        //if( empty($match->id_macro_match) ):
            /** Salvo i giocatori che hanno effettivamente giocato la partita, i titolari in quel momento */
            $teamPlayers = TeamPlayer::where('id_team', '=', $input['score-id-team1'])->get();
            foreach($teamPlayers as $teamPlayer):
                if( $teamPlayer->starter ):
                    $matchPlayer = new MatchPlayer;
                    $matchPlayer->id_match = $input['score-id-match'];
                    $matchPlayer->side = 'team1';
                    $matchPlayer->id_team = $input['score-id-team1'];;
                    $matchPlayer->id_player = $teamPlayer->id_player;
                    $matchPlayer->save();
                endif;
            endforeach;

            $teamPlayers = TeamPlayer::where('id_team', '=', $input['score-id-team2'])->get();
            foreach($teamPlayers as $teamPlayer):
                if( $teamPlayer->starter ):
                    $matchPlayer = new MatchPlayer;
                    $matchPlayer->id_match = $input['score-id-match'];
                    $matchPlayer->side = 'team2';
                    $matchPlayer->id_team = $input['score-id-team2'];
                    $matchPlayer->id_player = $teamPlayer->id_player;
                    $matchPlayer->save();
                endif;
            endforeach;
        //endif;


        /* Inserisco punteggio */
        for($set=1;$set<=5;$set++):

            if( isset($input['score-team1-set-'.$set]) && isset($input['score-team2-set-'.$set]) ):

                $score_t1 =  $input['score-team1-set-'.$set];
                $score_t2 =  $input['score-team2-set-'.$set];

                $games_won_t1 += $score_t1;
                $games_won_t2 += $score_t2;

                if($score_t1 > 0 || $score_t2 > 0):

                    $set_played ++;

                    $score = new Score;
                    $score->id_match = $input['score-id-match'];
                    $score->id_team = $input['score-id-team1'];
                    $score->side = 'team1';
                    $score->set = $set;
                    $score->points = $score_t1;
                    $score->save();

                    $score = new Score;
                    $score->id_match = $input['score-id-match'];
                    $score->id_team = $input['score-id-team2'];
                    $score->side = 'team2';
                    $score->set = $set;
                    $score->points = $score_t2;
                    $score->save();

                    /* Aggiorno punteggio macro squadra */

                    if( !empty($match->id_macro_match) ):
                        $score = new MacroScore;
                        $score->id_match = $match->id_macro_match;
                        $score->id_submatch = $match->id;
                        $score->id_team = $macroMatch->id_team1;
                        $score->side = 'team1';
                        $score->set = 1;
                        $score->points = $score_t1;
                        $score->save();


                        $score = new MacroScore;
                        $score->id_match = $match->id_macro_match;
                        $score->id_submatch = $match->id;
                        $score->id_team = $macroMatch->id_team2;
                        $score->side = 'team2';
                        $score->set = 1;
                        $score->points = $score_t2;
                        $score->save();
                    endif;


                    if( intval($score_t1) > intval($score_t2) ):
                        $set_won_t1 ++;
                    elseif( intval($score_t2) > intval($score_t1) ):
                        $set_won_t2 ++;
                    endif;

                endif;
            endif;
        endfor;

        /* Aggiorno la classifica */
        if( $set_played > 0):

            if($match->matchcodes->ref_type == 'round'):

                $id_group = Round::where('id', '=', $match->matchcodes->id_ref)->first()->id_group;
                $group = Group::where('id', '=', $id_group)->first();
                $id_edition = $group->division->tournament->edition->id;

                //Team 1 won, add 3 points
                if( $set_won_t1 > $set_won_t2 ):

                    $classification = new Classification;
                    $classification->id_group = $id_group;
                    $classification->id_match = $id_match;
                    $classification->id_team = $match->id_team1;
                    $classification->points = 3;
                    $classification->played = 1;
                    $classification->won = 1;
                    $classification->lost = 0;
                    $classification->draws = 0;
                    $classification->set_won = $set_won_t1;
                    $classification->set_lost = $set_won_t2;
                    $classification->games_won = $games_won_t1;
                    $classification->games_lost = $games_won_t2;
                    $classification->save();


                    $classification = new Classification;
                    $classification->id_group = $id_group;
                    $classification->id_match = $id_match;
                    $classification->id_team = $match->id_team2;
                    $classification->points = 0;
                    $classification->played = 1;
                    $classification->won = 0;
                    $classification->lost = 1;
                    $classification->draws = 0;
                    $classification->set_won = $set_won_t2;
                    $classification->set_lost = $set_won_t1;
                    $classification->games_won = $games_won_t2;
                    $classification->games_lost = $games_won_t1;
                    $classification->save();


                //Team 1 won, add 3 points
                elseif( $set_won_t2 > $set_won_t1 ):
                    $classification = new Classification;
                    $classification->id_group = $id_group;
                    $classification->id_match = $id_match;
                    $classification->id_team = $match->id_team2;
                    $classification->points = 3;
                    $classification->played = 1;
                    $classification->won = 1;
                    $classification->lost = 0;
                    $classification->draws = 0;
                    $classification->set_won = $set_won_t2;
                    $classification->set_lost = $set_won_t1;
                    $classification->games_won = $games_won_t2;
                    $classification->games_lost = $games_won_t1;
                    $classification->save();


                    $classification = new Classification;
                    $classification->id_group = $id_group;
                    $classification->id_match = $id_match;
                    $classification->id_team = $match->id_team1;
                    $classification->points = 0;
                    $classification->played = 1;
                    $classification->won = 0;
                    $classification->lost = 1;
                    $classification->draws = 0;
                    $classification->set_won = $set_won_t1;
                    $classification->set_lost = $set_won_t2;
                    $classification->games_won = $games_won_t1;
                    $classification->games_lost = $games_won_t2;
                    $classification->save();


                // Pareggio
                elseif( $set_won_t1 == $set_won_t2 ):

                    $classification = new Classification;
                    $classification->id_group = $id_group;
                    $classification->id_match = $id_match;
                    $classification->id_team = $match->id_team2;
                    $classification->points = 1;
                    $classification->played = 1;
                    $classification->won = 0;
                    $classification->lost = 0;
                    $classification->draws = 1;
                    $classification->set_won = $set_won_t2;
                    $classification->set_lost = $set_won_t1;
                    $classification->games_won = $games_won_t2;
                    $classification->games_lost = $games_won_t1;
                    $classification->save();


                    $classification = new Classification;
                    $classification->id_group = $id_group;
                    $classification->id_match = $id_match;
                    $classification->id_team = $match->id_team1;
                    $classification->points = 1;
                    $classification->played = 1;
                    $classification->won = 0;
                    $classification->lost = 0;
                    $classification->draws = 1;
                    $classification->set_won = $set_won_t1;
                    $classification->set_lost = $set_won_t2;
                    $classification->games_won = $games_won_t1;
                    $classification->games_lost = $games_won_t2;
                    $classification->save();

                endif;

                if( !empty($match->id_macro_match) ):


                    $macroMatch = MacroMatch::where('id', '=', $match->id_macro_match)->first();

                    if( $set_won_t1 > $set_won_t2 ):
                        $won_t1 = 1;
                        $won_t2 = 0;
                        $lost_t1 = 0;
                        $lost_t2 = 1;
                        $draw_t1 = 0;
                        $draw_t2 = 0;
                    elseif( $set_won_t2 > $set_won_t1 ):
                        $won_t1 = 0;
                        $won_t2 = 1;
                        $lost_t1 = 1;
                        $lost_t2 = 0;
                        $draw_t1 = 0;
                        $draw_t2 = 0;
                    elseif( $set_won_t1 == $set_won_t2 ):
                        $won_t1 = 0;
                        $won_t2 = 0;
                        $lost_t1 = 0;
                        $lost_t2 = 0;
                        $draw_t1 = 1;
                        $draw_t2 = 1;
                    endif;

                    /* Se campionato a squadre aggiorno la classifica della macro squadra */
                    $classification = new MacroClassification;
                    $classification->id_group = $id_group;
                    $classification->id_match = $macroMatch->id;
                    $classification->id_submatch = $match->id;
                    $classification->id_team = $macroMatch->id_team1;
                    $classification->points = $games_won_t1;
                    $classification->played = 1;
                    $classification->won = $won_t1;
                    $classification->lost = $lost_t1;
                    $classification->draws = $draw_t1;
                    $classification->set_won = $set_won_t1;
                    $classification->set_lost = $set_won_t2;;
                    $classification->games_won = $games_won_t1;
                    $classification->games_lost = $games_won_t2;
                    $classification->save();

                    $classification = new MacroClassification;
                    $classification->id_group = $id_group;
                    $classification->id_match = $macroMatch->id;
                    $classification->id_submatch = $match->id;
                    $classification->id_team = $macroMatch->id_team2;
                    $classification->points = $games_won_t2;
                    $classification->played = 1;
                    $classification->won = $won_t2;
                    $classification->lost = $lost_t2;
                    $classification->draws = $draw_t2;
                    $classification->set_won = $set_won_t2;
                    $classification->set_lost = $set_won_t1;;
                    $classification->games_won = $games_won_t2;
                    $classification->games_lost = $games_won_t1;
                    $classification->save();


                endif;



                /** Update Rankings */
                // foreach( $match->team1->players as $teamPlayer ):
                //     if($teamPlayer->starter):
                //         /*
                //         $ranking = Ranking::where('year', '=', Group::where('id', '=', $id_group)->first()->division->tournament->date_start->format('Y'))
                //                           ->where('id_player', '=', $teamPlayer->player->id)
                //                           ->first();
                //                           */

                //         $ranking = new Ranking;
                //         $ranking->id_player = $teamPlayer->player->id;
                //         $ranking->year = Group::where('id', '=', $id_group)->first()->division->tournament->date_start->format('Y');
                //         if( $match->date ):
                //             $ranking->date = $match->date;
                //         else:
                //             $ranking->date = date('Y-m-d');
                //         endif;
                //         $ranking->id_match = $match->id;
                //         $ranking->id_edition = $id_edition;
                //         if( $match->club ):
                //             $ranking->id_city = $match->club->id_city;
                //         else:
                //             $ranking->id_city = 0;
                //         endif;

                //         //Team 1 won
                //         if( $set_won_t1 > $set_won_t2 ):
                //             $ranking->match_won = 1;
                //             $ranking->match_lost = 0;
                //             $ranking->match_deuce = 0;

                //             // Team1 won 2 - 0
                //             if( $set_won_t1 == 2 && $set_won_t2 == 0 ):
                //                 $ranking->points = 20;
                //             elseif( $set_won_t1 == 2 && $set_won_t2 == 1 ):
                //                 $ranking->points = 15;
                //             endif;


                //         //Team 1 lost 1 - 2
                //         elseif( $set_won_t1 < $set_won_t2 ):
                //             $ranking->match_won = 0;
                //             $ranking->match_lost = 1;
                //             $ranking->match_deuce = 0;

                //             if( $set_won_t1 == 1 && $set_won_t2 == 2 ):
                //                 $ranking->points = 5;
                //             endif;

                //         //Deuce
                //         elseif( $set_won_t1 == $set_won_t2 ):
                //             $ranking->match_won = 0;
                //             $ranking->match_lost = 0;
                //             $ranking->match_deuce = 1;
                //         endif;

                //         $ranking->set_won = $set_won_t1;
                //         $ranking->set_lost = $set_won_t2;
                //         $ranking->games_won = $games_won_t1;
                //         $ranking->games_lost = $games_won_t2;
                //         $ranking->save();

                //     endif;
                // endforeach;

                /** Update Rankings */
                // foreach( $match->team2->players as $teamPlayer ):
                //     if($teamPlayer->starter):
                //         /*
                //         $ranking = Ranking::where('year', '=', Group::where('id', '=', $id_group)->first()->division->tournament->date_start->format('Y'))
                //                           ->where('id_player', '=', $teamPlayer->player->id)
                //                           ->first();*/


                //         $ranking = new Ranking;
                //         $ranking->id_player = $teamPlayer->player->id;
                //         $ranking->year = Group::where('id', '=', $id_group)->first()->division->tournament->date_start->format('Y');
                //         if( $match->date ):
                //             $ranking->date = $match->date;
                //         else:
                //             $ranking->date = date('Y-m-d');
                //         endif;
                //         $ranking->id_match = $match->id;
                //         $ranking->id_edition = $id_edition;
                //         if( $match->club ):
                //             $ranking->id_city = $match->club->id_city;
                //         else:
                //             $ranking->id_city = 0;
                //         endif;

                //         //Team 2 won
                //         if( $set_won_t2 > $set_won_t1 ):
                //             $ranking->match_won = 1;
                //             $ranking->match_lost = 0;
                //             $ranking->match_deuce = 0;

                //             if( $set_won_t2 == 2 && $set_won_t1 == 0 ):
                //                 $ranking->points = 20;
                //             elseif( $set_won_t2 == 2 && $set_won_t1 == 1 ):
                //                 $ranking->points = 15;
                //             endif;


                //         //Team 2 lost
                //         elseif( $set_won_t2 < $set_won_t1 ):
                //             $ranking->match_won = 0;
                //             $ranking->match_lost = 1;
                //             $ranking->match_deuce = 0;

                //             if( $set_won_t1 == 1 && $set_won_t2 == 2 ):
                //                 $ranking->points = 5;
                //             endif;

                //         //Deuce
                //         elseif( $set_won_t2 == $set_won_t1 ):
                //             $ranking->match_won = 0;
                //             $ranking->match_lost = 0;
                //             $ranking->match_deuce = 1;
                //         endif;
                //         $ranking->set_won = $set_won_t2;
                //         $ranking->set_lost = $set_won_t1;
                //         $ranking->games_won = $games_won_t2;
                //         $ranking->games_lost = $games_won_t1;
                //         $ranking->save();


                //     endif;
                // endforeach;

            elseif($match->matchcodes->ref_type == 'phase'):

                $curPhase = Phase::where('id', '=', $match->matchcodes->id_ref)->first();
                $curPhaseMatches = Match::where('matchcode', '=', $curPhase->matchcode)->orderBy('id', 'ASC')->get();

                $side = false;

                foreach($curPhaseMatches as $curPhaseMatch):
                    if($curPhaseMatch->id == $match->id):
                        break;
                    else:
                        $side = !$side;
                    endif;

                endforeach;

                $nextPhase = Phase::where( 'id_bracket',     '=', $curPhase->id_bracket  )->where('name', '=', ($curPhase->name+1) )->first();

                if($nextPhase):
                    $newMatch  = Match::where( 'prev_matchcode', '=', $match->next_matchcode )->first();

                    //Team 1 won, add 3 points
                    if( $set_won_t1 > $set_won_t2 ):

                        if($side): $newMatch->id_team2 = $match->id_team1; else: $newMatch->id_team1 = $match->id_team1; endif;

                    // Team 2 won
                    elseif( $set_won_t2 > $set_won_t1 ):

                        if($side): $newMatch->id_team2 = $match->id_team2; else: $newMatch->id_team1 = $match->id_team2;  endif;

                    endif;

                    $newMatch->save();

                endif;

                /** Update Rankings */
                // foreach( $match->team1->players as $teamPlayer ):
                //     if($teamPlayer->starter):
                //         /*
                //         $ranking = Ranking::where('year', '=', $curPhase->bracket->tournament->date_start->format('Y'))
                //                           ->where('id_player', '=', $teamPlayer->player->id)
                //                           ->first(); */

                //         /*
                //         $ranking = new Ranking;
                //         $ranking->id_player = $teamPlayer->player->id;
                //         $ranking->year = $curPhase->bracket->tournament->date_start->format('Y');
                //         $ranking->date = $match->date;
                //         $ranking->points = $ranking->points + ( $games_won_t1 + ( 5 * $curPhase->name ));
                //         $ranking->id_match = $match->id;
                //         $ranking->id_city = $match->club->id_city;

                //         //Team 1 won
                //         if( $set_won_t1 > $set_won_t2 ):
                //             $ranking->match_won = 1;
                //             $ranking->match_lost = 0;
                //             $ranking->match_deuce = 0;
                //         //Team 1 lost
                //         elseif( $set_won_t1 < $set_won_t2 ):
                //             $ranking->match_won = 0;
                //             $ranking->match_lost = 1;
                //             $ranking->match_deuce = 0;
                //         endif;

                //         $ranking->set_won = $set_won_t1;
                //         $ranking->set_lost = $set_won_t2;
                //         $ranking->games_won = $games_won_t1;
                //         $ranking->games_lost = $games_won_t2;
                //         $ranking->save();
                //         */


                //     endif;
                // endforeach;

                /** Update Rankings */
                // foreach( $match->team2->players as $teamPlayer ):
                //     if($teamPlayer->starter):
                //         /*$ranking = Ranking::where('year', '=', $curPhase->bracket->tournament->date_start->format('Y'))
                //                           ->where('id_player', '=', $teamPlayer->player->id)
                //                           ->first();*/

                //         /*
                //         $ranking = new Ranking;
                //         $ranking->id_player = $teamPlayer->player->id;
                //         $ranking->year = $curPhase->bracket->tournament->date_start->format('Y');
                //         $ranking->date = $match->date;
                //         $ranking->points = $ranking->points + ( $games_won_t2 + ( 5 * $curPhase->name ));
                //         $ranking->id_match = $match->id;
                //         $ranking->id_city = $match->club->id_city;

                //         //Team 2 won
                //         if( $set_won_t2 > $set_won_t1 ):
                //             $ranking->match_won = 1;
                //             $ranking->match_lost = 0;
                //             $ranking->match_deuce = 0;
                //         //Team 2 lost
                //         elseif( $set_won_t2 < $set_won_t1 ):
                //             $ranking->match_won = 0;
                //             $ranking->match_lost = 1;
                //             $ranking->match_deuce = 0;
                //         endif;
                //         $ranking->set_won = $set_won_t2;
                //         $ranking->set_lost = $set_won_t1;
                //         $ranking->games_won = $games_won_t2;
                //         $ranking->games_lost = $games_won_t1;
                //         $ranking->save();
                //         */


                //     endif;
                // endforeach;

            endif;
        endif;

        /*
        $rankings_m = DB::table('rankings')
                        ->join('users', 'users.id', '=', 'rankings.id_player')
                        ->select(DB::raw('id_player', 'sum(points) as points'))
                        ->where('users.gender', '=', 'm')
                        ->groupBy('rankings.id_player')
                        ->orderBy('points', 'DESC')
                        ->get();
                        */

        // $rankings_m = Ranking::selectRaw('id_player, sum(points) as points, sum(match_won) match_won, sum(match_lost) match_lost, sum(set_won) set_won, sum(set_lost) set_lost, sum(games_won) games_won, sum(games_lost) games_lost')
        //                 ->join('users', 'users.id', '=', 'rankings.id_player')
        //                 ->where('date', '>', Carbon::now()->subYear(1))
        //                 ->where('users.gender', '=', 'm')
        //                 ->groupBy('id_player')
        //                 ->orderBy('points', 'DESC')
        //                 ->get()
        //                 ;

        /*
        $rankings_f = DB::table('rankings')
                        ->join('users', 'users.id', '=', 'rankings.id_player')
                        ->select(DB::raw('id_player', 'sum(points) as points'))
                        ->where('users.gender', '=', 'f')
                        ->groupBy('rankings.id_player')
                        ->orderBy('points', 'DESC')
                        ->get();
                        */

        // $rankings_f = Ranking::selectRaw('id_player, sum(points) as points, sum(match_won) match_won, sum(match_lost) match_lost, sum(set_won) set_won, sum(set_lost) set_lost, sum(games_won) games_won, sum(games_lost) games_lost')
        //                 ->join('users', 'users.id', '=', 'rankings.id_player')
        //                 ->where('date', '>', Carbon::now()->subYear(1))
        //                 ->where('users.gender', '=', 'f')
        //                 ->groupBy('id_player')
        //                 ->orderBy('points', 'DESC')
        //                 ->get()
        //                 ;

        /*
        $rankings = Ranking::selectRaw('sum(points) as points, id_player')
                            ->where('date', '>', Carbon::now()->subYear(1))
                            ->
                            ->groupBy('id_player')
                            ->orderBy('points', 'DESC')
                            ->get();
                            */

        // foreach( $rankings_m as $position => $ranking ):
        //     $user = User::where('id', '=', $ranking->id_player)->first();
        //     $user->position = $position+1;
        //     $user->save();
        // endforeach;

        // foreach( $rankings_f as $position => $ranking ):
        //     $user = User::where('id', '=', $ranking->id_player)->first();
        //     $user->position = $position+1;
        //     $user->save();
        // endforeach;

        if( isset($input['ajax']) && $input['ajax'] == 1 )
            return response()->json(array('status' => 'ok'));
        else
            return back();
    }

    /**
     * Display the specified Score.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $score = $this->scoreRepository->find($id);

        if (empty($score)) {
            Flash::error('Score not found');

            return redirect(route('scores.index'));
        }

        return view('admin.scores.show')->with('score', $score);
    }

    public function getScore($id_match){
        $score = Score::where('id_match', '=',  $id_match)->get();
        $match = Match::where('id', '=', $id_match)->first();

        $risultato = [];
        foreach($score as $s){
            if($s->id_team == $match->id_team1):
                $risultato[$s->set]['team1'] = $s->points;
            elseif($s->id_team == $match->id_team2):
                $risultato[$s->set]['team2'] = $s->points;
            endif;
        }

        return response()->json($risultato);
    }


    public function forfait($id_match, Request $request){
        $input = $request->all();
        $id_team_winner = $input['id_team_winner'];
        $id_team_loser = $input['id_team_loser'];

        $match = Match::where('id', '=', $id_match)->first();
        if($match->id_team1 == $id_team_winner):
            $side_winner = 'team1';
            $side_loser = 'team2';
        else:
            $side_winner = 'team2';
            $side_loser = 'team1';
        endif;


        Score::where('id_match', '=', $id_match)->delete();
        Classification::where('id_match', '=', $id_match)->delete();
        MatchPlayer::where('id_match', '=', $id_match)->delete();


        /** Salvo i giocatori che hanno effettivamente giocato la partita, i titolari in quel momento */
        $teamPlayers = TeamPlayer::where('id_team', '=', $id_team_winner)->get();
        foreach($teamPlayers as $teamPlayer):
            if( $teamPlayer->starter ):
                $matchPlayer = new MatchPlayer;
                $matchPlayer->id_match = $id_match;
                $matchPlayer->id_team = $id_team_winner;
                $matchPlayer->side = $side_winner;
                $matchPlayer->id_player = $teamPlayer->id_player;
                $matchPlayer->save();
            endif;
        endforeach;

        $teamPlayers = TeamPlayer::where('id_team', '=', $id_team_loser)->get();
        foreach($teamPlayers as $teamPlayer):
            if( $teamPlayer->starter ):
                $matchPlayer = new MatchPlayer;
                $matchPlayer->id_match = $id_match;
                $matchPlayer->id_team = $id_team_loser;
                $matchPlayer->side = $side_loser;
                $matchPlayer->id_player = $teamPlayer->id_player;
                $matchPlayer->save();
            endif;
        endforeach;

        $match = Match::find($id_match);

        if( empty($match->id_macro_match) ):

            /** Assegno il risultato di 6-0 6-0 */
            $score = new Score;
            $score->id_match = $id_match;
            $score->id_team = $id_team_winner;
            $score->side = $side_winner;
            $score->set = 1;
            $score->points = 6;
            $score->save();

            $score = new Score;
            $score->id_match = $id_match;
            $score->id_team = $id_team_loser;
            $score->side = $side_loser;
            $score->set = 1;
            $score->points = 0;
            $score->save();

            $score = new Score;
            $score->id_match = $id_match;
            $score->id_team = $id_team_winner;
            $score->side = $side_winner;
            $score->set = 2;
            $score->points = 6;
            $score->save();

            $score = new Score;
            $score->id_match = $id_match;
            $score->id_team = $id_team_loser;
            $score->side = $side_loser;
            $score->set = 2;
            $score->points = 0;
            $score->save();

        else:

            /** Assegno il risultato di 7-0 */
            $score = new Score;
            $score->id_match = $id_match;
            $score->id_team = $id_team_winner;
            $score->side = $side_winner;
            $score->set = 1;
            $score->points = 7;
            $score->save();

            $score = new Score;
            $score->id_match = $id_match;
            $score->id_team = $id_team_loser;
            $score->side = $side_loser;
            $score->set = 1;
            $score->points = 0;
            $score->save();

        endif;

        if( $match->matchcodes->ref_type == 'round' ){

            /** Se la partita + della fase a gironi aggiorno la classifica */
            $group = $match->matchcodes->matchRound->group;

            $match->a_tavolino = 1;
            $match->id_club = $group->division->zone->clubs[0]->id_club;  //Assegno il primo circolo della zona in automatico
            $match->date = date('Y-m-d');
            $match->save();

            $classification = new Classification;
            $classification->id_group = $group->id;
            $classification->id_match = $id_match;
            $classification->id_team = $id_team_winner;
            $classification->points = 3;
            $classification->played = 1;
            $classification->won = 1;
            $classification->lost = 0;
            $classification->draws = 0;
            $classification->set_won = 2;
            $classification->set_lost = 0;
            $classification->games_won = 12;
            $classification->games_lost = 0;
            $classification->save();

            $classification = new Classification;
            $classification->id_group = $group->id;
            $classification->id_match = $id_match;
            $classification->id_team = $id_team_loser;
            $classification->points = 0;
            $classification->played = 1;
            $classification->won = 0;
            $classification->lost = 1;
            $classification->draws = 0;
            $classification->set_won = 0;
            $classification->set_lost = 2;
            $classification->games_won = 0;
            $classification->games_lost = 12;
            $classification->save();


        }elseif( $match->matchcodes->ref_type == 'phase' ){

            /** Se la partita è di tabellone faccio avanzare la squadra vincitrice al prossimo incontro */

            $curPhase = Phase::where('id', '=', $match->matchcodes->id_ref)->first();
            $curPhaseMatches = Match::where('matchcode', '=', $curPhase->matchcode)->orderBy('id', 'ASC')->get();

            $side = false;

            foreach($curPhaseMatches as $curPhaseMatch):
                if($curPhaseMatch->id == $match->id):
                    break;
                else:
                    $side = !$side;
                endif;

            endforeach;

            $nextPhase = Phase::where( 'id_bracket',     '=', $curPhase->id_bracket  )->where('name', '=', ($curPhase->name+1) )->first();

            if($nextPhase):
                $newMatch  = Match::where( 'prev_matchcode', '=', $match->next_matchcode )->first();

                if($side):
                    $newMatch->id_team2 = $id_team_winner;
                else:
                    $newMatch->id_team1 = $id_team_winner;
                endif;

                $newMatch->save();
            endif;

        }

        return response()->json(array('status' => 'ok'));

    }

    public function forfaitMacro($id_macro_match, Request $request){
        $input = $request->all();
        $id_team_winner = $input['id_team_winner'];
        $id_team_loser = $input['id_team_loser'];

        $macroMatch = MacroMatch::find($id_macro_match);

        if($macroMatch->id_team1 == $id_team_winner):
            $side_winner = 'team1';
            $side_loser = 'team2';
        else:
            $side_winner = 'team2';
            $side_loser = 'team1';
        endif;


        MacroScore::where('id_match', '=', $id_macro_match)->delete();
        MacroClassification::where('id_match', '=', $id_macro_match)->delete();


        /** Assegno il risultato di 55-0 */

        $score = new MacroScore;
        $score->id_match = $id_macro_match;
        $score->id_submatch = 0;
        $score->id_team = $id_team_winner;
        $score->side = $side_winner;
        $score->set = 1;
        $score->points = 35;
        $score->save();

        $score = new MacroScore;
        $score->id_match = $id_macro_match;
        $score->id_submatch = 0;
        $score->id_team = $id_team_loser;
        $score->side = $side_loser;
        $score->set = 1;
        $score->points = 0;
        $score->save();


        if( $macroMatch->matchcodes->ref_type == 'round' ){

            /** Se la partita è della fase a gironi aggiorno la classifica */
            $group = $macroMatch->matchcodes->matchRound->group;

            $macroMatch->a_tavolino = 1;
            $macroMatch->id_club = $group->division->zone->clubs[0]->id_club;  //Assegno il primo circolo della zona in automatico
            $macroMatch->date = date('Y-m-d');
            $macroMatch->save();

            $classification = new MacroClassification;
            $classification->id_group = $group->id;
            $classification->id_match = $id_macro_match;
            $classification->id_team = $id_team_winner;
            $classification->points = 55;
            $classification->played = 1;
            $classification->won = 1;
            $classification->lost = 0;
            $classification->draws = 0;
            $classification->set_won = 1;
            $classification->set_lost = 0;
            //$classification->mini_won = 5;
            //$classification->mini_lost = 0;
            $classification->games_won = 55;
            $classification->games_lost = 0;
            $classification->save();

            $classification = new MacroClassification;
            $classification->id_group = $group->id;
            $classification->id_match = $id_macro_match;
            $classification->id_team = $id_team_loser;
            $classification->points = 0;
            $classification->played = 1;
            $classification->won = 0;
            $classification->lost = 1;
            $classification->draws = 0;
            $classification->set_won = 0;
            $classification->set_lost = 1;
            //$classification->mini_won = 0;
            //$classification->mini_lost = 5;
            $classification->games_won = 0;
            $classification->games_lost = 55;
            $classification->save();

        }elseif( $macroMatch->matchcodes->ref_type == 'phase' ){

            /** Se la partita è di tabellone faccio avanzare la squadra vincitrice al prossimo incontro */

            $curPhase = Phase::where('id', '=', $match->matchcodes->id_ref)->first();
            $curPhaseMatches = MacroMatch::where('matchcode', '=', $curPhase->matchcode)->orderBy('id', 'ASC')->get();

            $side = false;

            foreach($curPhaseMatches as $curPhaseMatch):
                if($curPhaseMatch->id == $match->id):
                    break;
                else:
                    $side = !$side;
                endif;

            endforeach;

            $nextPhase = Phase::where( 'id_bracket',     '=', $curPhase->id_bracket  )->where('name', '=', ($curPhase->name+1) )->first();

            if($nextPhase):
                $newMatch  = MacroMatch::where( 'prev_matchcode', '=', $macroMatch->next_matchcode )->first();

                if($side):
                    $newMatch->id_team2 = $id_team_winner;
                else:
                    $newMatch->id_team1 = $id_team_winner;
                endif;

                $newMatch->save();
            endif;

        }


        /* Assegno la vittoria a tavolino a tutti i mini incontri */    	/*
        foreach($macroMatch->subMatches as $match):

            $myRequest = new \Illuminate\Http\Request();
            $myRequest->setMethod('POST');
            $myRequest->request->add(['_token' => $request->session()->token()]);

            if( $side_winner == 'team1' ):
                $myRequest->request->add(['id_team_winner' => $match->id_team1]);
                $myRequest->request->add(['id_team_loser' => $match->id_team2]);
            elseif( $side_winner == 'team2' ):
                $myRequest->request->add(['id_team_winner' => $match->id_team2]);
                $myRequest->request->add(['id_team_loser' => $match->id_team1]);
            endif;

            $namespace = 'App\Http\Controllers\Admin';
            $controller = app()->make($namespace.'\ScoreController');
            $controller->callAction('forfait',[$match->id, $myRequest]);

        endforeach;     	*/

        return response()->json(array('status' => 'ok'));

    }

    function delForfaitMacro($id_macro_match){
        $macroMatch = MacroMatch::find($id_macro_match);
        $macroMatch->a_tavolino = 0;
        $macroMatch->save();

        MacroScore::where('id_match', '=', $id_macro_match)->delete();
        MacroClassification::where('id_match', '=', $id_macro_match)->delete();

        foreach($macroMatch->subMatches as $match):
            Score::where('id_match', '=', $match->id)->delete();
            Classification::where('id_match', '=', $match->id)->delete();
            Ranking::where('id_match', '=', $match->id)->delete();
            MatchPlayer::where('id_match', '=', $match->id)->delete();

            $match->a_tavolino = 0;
            $match->save();
        endforeach;

        return Response()->json(array('status'=>'ok'));
    }

    function storeAll($id_amcro_match, Request $request){
        $input = $request->all();

        $scores = [];
        foreach($input as $k => $val):
            if( $k !== '_token' && !is_null($val) && $val >= 0 ):
                preg_match('/score_team(\d)_(\d+)_match_(\d+)/', $k, $output);
                $scores[$output[3]][$output[1]][$output[2]] = $val;
            endif;
        endforeach;

        //dump(json_encode($scores));

        /*
            score-id-match
            score-id-team1
            score-id-team2
            score-team1-set-1
            score-team2-set-1
         */

        $error = false;
        foreach($scores as $id_match => $score):
            $scoreController = new ScoreController;

            $request = new Request;
            $request['ajax'] = 1;
            $request['_token'] = $input['_token'];
            $request['score-id-match'] = $id_match;
            $request['score-id-team1'] = array_keys($scores[$id_match][1])[0];
            $request['score-id-team2'] = array_keys($scores[$id_match][2])[0];
            $request['score-team1-set-1'] = intval($scores[$id_match][1][array_keys($scores[$id_match][1])[0]]);
            $request['score-team2-set-1'] = intval($scores[$id_match][2][array_keys($scores[$id_match][2])[0]]);
            $res = $scoreController->store($request);
        endforeach;

        echo json_encode(array('status' => 'OK'));
    }
}
