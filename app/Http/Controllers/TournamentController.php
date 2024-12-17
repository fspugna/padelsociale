<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Tournament;
use App\Models\Division;
use App\Models\Bracket;
use App\Models\Phase;
use App\Models\PhaseTeam;
use App\Models\Zone;
use App\Models\Group;
use App\Models\Classification;
use App\Models\Round;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Match;
use App\Models\Team;
use App\Models\Image;
use App\Models\CategoryType;
use App\Models\Category;

use App\Models\MatchPlayer;
use App\Models\MacroTeam;
use App\Models\MacroSubscription;


class TournamentController extends Controller
{
    public function show($id_tournament){

       $tournament = Tournament::where('id', '=', $id_tournament)->first();
       $tournament['srcImgFeaturedBig'] = asset('storage/'.$tournament->edition->logo);
       $tournament['srcImgFeaturedBigx2'] = asset('storage/'.$tournament->edition->logo);

       $tabs = [];
       foreach($tournament->edition->categoryTypes as $k => $ct):
            $ct_val = [ 'label' => $ct->categoryType->name , 'id' => $ct->categoryType->id , 'group' => 'id_category_type' ];
            if($k == 0):
                $ct_val ['checked'] = 'checked';
            endif;
            $tabs [] = $ct_val;
       endforeach;

       return view('page-torneo')
                ->with('tournament', $tournament)
                ->with('tabs_options', $tabs)
                ->with('categoryTypes', $tournament->edition->categoryTypes)
                ;
    }

    public function categories(Request $request){
        $input = $request->all();

        $tournament = Tournament::where('id', '=', $input['id_tournament'])->first();

        $res_categories = null;

        if($tournament->id_tournament_type == 1):

            $res_categories = Division::where('id_tournament', '=', $input['id_tournament'])
                                ->where('id_zone', '=', $input['select-zone'])
                                ->get();

        elseif($tournament->id_tournament_type == 2):

            $res_categories = Bracket::where('id_tournament', '=', $input['id_tournament'])
                                        ->where('id_zone', '=', $input['select-zone'])
                                        ->get();

        endif;

        $categories = [];
        foreach($res_categories as $c):
            $categories[$c->categoryType->name][] = $c;
        endforeach;

        $zone = Zone::where('id', '=', $input['select-zone'])->first();

        //dd($categories);

        return view('page-zona')->with('categories', $categories)
                                ->with('tournament', $tournament)
                                ->with('zone', $zone)
                                ;
    }

    public function showZone($id_tournament, $id_zone){

        $tournament = Tournament::where('id', '=', $id_tournament)->first();
        if($tournament->id_tournament_type == 1):
            $group = Group::where('id_division', '=', Division::where('id_tournament', '=', $id_tournament)
                                                              ->where('id_zone', '=', $id_zone)
                                                              ->first()->id)
                            ->where('flag_online', '=', 1)
                            ->orderBy('name', 'ASC')
                            ->first();

            if( $group ):
                return redirect(route('round_show', ['id_tournament' => $id_tournament, 'id_zone' => $id_zone, 'id_category_type' => $tournament->edition->categoryTypes[0]->id_category_type, 'id_category' => $tournament->edition->categories[0]->id_category, 'id_group' => $group->id, 'id_round' => 0]));
            else:
                return redirect(route('round_show', ['id_tournament' => $id_tournament, 'id_zone' => $id_zone, 'id_category_type' => $tournament->edition->categoryTypes[0]->id_category_type, 'id_category' => $tournament->edition->categories[0]->id_category, 'id_group' => 0, 'id_round' => 0 ]));
            endif;
        elseif($tournament->id_tournament_type == 2):
            $bracket = Bracket::where('id_tournament', '=', $id_tournament)
                               ->where('id_zone', '=', 1)
                               ->where('flag_online', '=', 1)
                                ->first();
            if( $bracket ):
                return redirect(route('bracket_show', ['id_tournament' => $id_tournament, 'id_zone' => $id_zone, 'id_category_type' => $tournament->edition->categoryTypes[0]->id_category_type, 'id_category' => $tournament->edition->categories[0]->id_category, 'id_bracket' => $bracket->id, 'id_phase' => 0]));
            else:
                return redirect(route('bracket_show', ['id_tournament' => $id_tournament, 'id_zone' => $id_zone, 'id_category_type' => $tournament->edition->categoryTypes[0]->id_category_type, 'id_category' => $tournament->edition->categories[0]->id_category, 'id_bracket' => 0, 'id_phase' => 0 ]));
            endif;
        endif;

     }

     public function showCategoryType($id_tournament, $id_zone, $id_category_type){

        $tournament = Tournament::where('id', '=', $id_tournament)->first();
        if($tournament->id_tournament_type == 1):
            $group = Group::where('id_division', '=', Division::where('id_tournament', '=', $id_tournament)->first()->id)->first();
            if( $group ):
                return redirect(route('group_show', ['id_tournament' => $id_tournament, 'id_zone' => $id_zone, 'id_category_type' => $id_category_type, 'id_category' => $tournament->edition->categories[0]->id_category, 'id_group' => $group->id, 'id_round' => 0]));
            else:
                return redirect(route('group_show', ['id_tournament' => $id_tournament, 'id_zone' => $id_zone, 'id_category_type' => $id_category_type, 'id_category' => $tournament->edition->categories[0]->id_category, 'id_group' => 0, 'id_round' => 0 ]));
            endif;
        elseif($tournament->id_tournament_type == 2):
            $bracket = Bracket::where('id_tournament', '=', $id_tournament)->where('flag_online', '=', 1)->first();
            if( $bracket ):
                return redirect(route('bracket_show', ['id_tournament' => $id_tournament, 'id_zone' => $id_zone, 'id_category_type' => $id_category_type, 'id_category' => $tournament->edition->categories[0]->id_category, 'id_bracket' => $bracket->id, 'id_phase' => 0]));
            else:
                return redirect(route('bracket_show', ['id_tournament' => $id_tournament, 'id_zone' => $id_zone, 'id_category_type' => $id_category_type, 'id_category' => $tournament->edition->categories[0]->id_category, 'id_bracket' => 0, 'id_phase' => 0 ]));
            endif;
        endif;

    }

    public function showCategory($id_tournament, $id_zone, $id_category_type, $id_category){

        $tournament = Tournament::where('id', '=', $id_tournament)->first();

        if ($id_category == 0):
            return redirect(route('group_show', ['id_tournament' => $id_tournament, 'id_zone' => $id_zone, 'id_category_type' => $id_category_type, 'id_category' => $id_category, 'id_group' => 0, 'id_round' => 0 ]));
        else:
            $division = Division::where('id_tournament', '=', $id_tournament)
                                    ->where('id_zone', '=', $id_zone)
                                    ->where('id_category_type', '=', $id_category_type)
                                    ->where('id_category', '=', $id_category)
                                    ->first();

            if($division):
                $group = Group::where('id_division', '=', $division->id)->first();

                if( $group ):
                    return redirect(route('round_show', ['id_tournament' => $id_tournament, 'id_zone' => $id_zone, 'id_category_type' => $id_category_type, 'id_category' => $id_category, 'id_group' => $group->id, 'id_round' => 0]));
                else:
                    return redirect(route('round_show', ['id_tournament' => $id_tournament, 'id_zone' => $id_zone, 'id_category_type' => $id_category_type, 'id_category' => $id_category, 'id_group' => 0, 'id_round' => 0 ]));
                endif;
            else:
                return redirect(route('bracket_show', ['id_tournament' => $id_tournament, 'id_zone' => $id_zone, 'id_category_type' => $id_category_type, 'id_category' => $id_category, 'id_bracket' => 0, 'id_phase' => 0 ]));
            endif;
        endif;

    }


    public function showGroup($id_tournament, $id_zone, $id_category_type, $id_category, $id_group){

        $tournament = Tournament::where('id', '=', $id_tournament)->first();
        $edition = $tournament->edition;

        if( in_array( $edition->edition_type , [0, 1] ) ):
            return $this->showGroupDouble($id_tournament, $id_zone, $id_category_type, $id_category, $id_group);
        elseif( $edition->edition_type == 2 ):
            return $this->showGroupMacro($id_tournament, $id_zone, $id_category_type, $id_category, $id_group, 0);
        endif;

    }

    public function showRound($id_tournament, $id_zone, $id_category_type, $id_category, $id_group, $id_round){

        $tournament = Tournament::where('id', '=', $id_tournament)->first();
        $edition = $tournament->edition;

        if( in_array($edition->edition_type, [0,1]) ):
            return $this->showGroup($id_tournament, $id_zone, $id_category_type, $id_category, $id_group);
        elseif( $edition->edition_type == 2 ):
            return $this->showGroupMacro($id_tournament, $id_zone, $id_category_type, $id_category, $id_group, $id_round);
        endif;

    }


    public function showGroupDouble($id_tournament, $id_zone, $id_category_type, $id_category, $id_group){

        $tournament = Tournament::where('id', '=', $id_tournament)->first();
        $tournament['srcImgFeaturedBig'] = asset('storage/'.$tournament->edition->logo);
        $tournament['srcImgFeaturedBigx2'] = asset('storage/'.$tournament->edition->logo);

        $edition = $tournament->edition;

        $division = Division::where('id_tournament', '=', $tournament->id)
                                ->where('id_zone', '=', $id_zone)
                                ->where('id_category_type', '=', $id_category_type)
                                ->where('id_category', '=', $id_category)
                                ->first();

        if($division):
            $groups = Group::where('id_division', '=', $division->id)->where('flag_online', '=', 1)->get();
        else:
            $groups = null;
        endif;


        $tabelloni = Tournament::where('id_tournament_type', '=', 2)
                                ->where('id_edition', '=', $tournament->id_edition)
                                ->where('id', '>', $id_tournament)
                                ->get();

        $bracket = null;
        $id_bracket = null;

        $brackets = [];
        foreach($tabelloni as $tabellone):
            $b = Bracket::where('id_tournament', '=', $tabellone->id)
                                        ->where('id_zone', '=', $id_zone)
                                        ->where('id_category_type', '=', $id_category_type)
                                        ->where('id_category', '=', $id_category)
                                        ->where('flag_online', '=', 1)
                                        ->first();

            $brackets[] = $b;
        endforeach;

        $group = Group::where('id', '=' ,$id_group)->where('flag_online', '=', 1)->first();

        $rounds = null;
        $classification = null;

        if($group):

            $res_classification = Group::selectRaw('gt.id_team, ifnull(sum(points), 0) as points, ifnull(sum(played), 0) as played, ifnull(sum(won), 0) as won, ifnull(sum(lost), 0) as lost, ifnull(sum(draws), 0) as draws, ifnull(sum(set_won), 0) as set_won, ifnull(sum(set_lost), 0) as set_lost, ifnull(sum(games_won), 0) as games_won, ifnull(sum(games_lost), 0) as games_lost')
                                    ->leftjoin('group_teams as gt', 'groups.id', '=', 'gt.id_group')
                                    ->leftJoin('classifications as c', function($join){
                                        $join->on('gt.id_group', '=', 'c.id_group')
                                             ->on('gt.id_team', '=', 'c.id_team');
                                    })
                                    ->where('groups.id', '=', $id_group)
                                    ->groupBy('gt.id_team')
                                    ->orderBy('points', 'DESC')
                                    ->orderByRaw('ifnull(sum(set_won), 0) - ifnull(sum(set_lost), 0) DESC')
                                    ->orderByRaw('ifnull(sum(games_won), 0) - ifnull(sum(games_lost), 0) DESC')
                                    ->get();

            $classification = [];
            $classifications_points = [];

            foreach($res_classification as $i => $c){
                $team = Team::where('id', '=', $c->id_team)->first();
                foreach($team->players as $player){
                    $classification[$i]['players'][] = $player;
                }
                $classification[$i]['classification'] = $c->toArray();

                if( !isset($classifications_points[$c->points]) )
                    $classifications_points[$c->points] = 1;
                else
                    $classifications_points[$c->points]++;
            }


            /* Calcolo scontri diretti */
            foreach($classification as $i => $c):
                if( $i < count($classification)-1):

                    /* Calcolo scontro diretto solo se sono 2 squadre a pari punti */
                    if( $classifications_points[ $c['classification']['points'] ] == 2 ):

                        if( $c['classification']['points'] == $classification[$i+1]['classification']['points'] && isset($group->id)):

                            //dd($c, $classification[$i+1]);
                            $risultato = Classification::where('id_group', $group->id)
                                                        ->where(function ($query) use ($c, $classification, $i) {
                                                            $query->where('id_team', '=', $c['classification']['id_team'])
                                                                  ->orWhere('id_team', '=', $classification[$i+1]['classification']['id_team']);
                                                        })->get();

                            if( $risultato ):

                                $ris = [];
                                foreach($risultato as $r):
                                    if( empty($ris[$r->id_match]['teams']) )
                                        $ris[$r->id_match]['teams'] = [];

                                    $ris[$r->id_match]['teams'][] = $r->id_team;

                                    if($r->points == 3){
                                        $ris[$r->id_match]['winner'] = $r->id_team;
                                    }
                                endforeach;

                                $winner = null;
                                foreach($ris as $r):
                                    if( count($r['teams']) == 2 && isset($r['winner']))
                                        $winner = $r['winner'];
                                endforeach;

                                if($winner !== null):
                                    if( $c['classification']['id_team'] != $winner):
                                        //dd($c['classification']['id_team'] , $winner, $classification);
                                        $appo = $classification[$i];
                                        $classification[$i] = $classification[$i+1];
                                        $classification[$i+1] = $appo;
                                    endif;

                                endif;
                            endif;
                        endif;

                        $i = $i + 2;

                    endif;

                    /*
                    elseif( $classifications_points[ $c['classification']['points'] ] > 2 ):

                        /** Se ho più di 2 squadre con gli stessi punti
                         * calcolo la differenza set e games
                         * degli incontri tra queste sole squadre
                         *

                        $teams_id = [];
                        $diff_scores = [];

                        foreach($classification as $c2):

                            if( $c2['classification']['points'] == $c['classification']['points'] ):
                                $teams_id[] = $c2['classification']['id_team'];
                            endif;

                        endforeach;

                        $rounds = Round::where('id_group', $group->id)->get();
                        /** Carico i risultati dei soli incontri tra le squadre coinvolte *
                        foreach($rounds as $round):
                            foreach($round->matches as $match):
                                if(in_array($match->id_team1, $teams_id) && in_array($match->id_team2, $teams_id)):
                                    foreach($match->scores as $score):
                                        $diff_scores[$score->id_match][$score->set][$score->id_team] = $score->points;
                                    endforeach;
                                endif;
                            endforeach;
                        endforeach;

                        $scores_between = [];

                        /** Calcolo il punteggio in base a set vinti e games vinti *
                        foreach($diff_scores as $match_id => $match):
                            foreach($match as $set => $points):
                                if( $points[array_keys($points)[0]] > $points[array_keys($points)[1]] ):
                                    if( !isset($scores_between[array_keys($points)[0]]) ):
                                        $scores_between[array_keys($points)[0]] = 0;
                                    endif;

                                    //Aggiungo 1000 punti per un set vinto
                                    $scores_between[array_keys($points)[0]]+=1000;
                                    //Aggiungo 100 punti * games vinti
                                    $scores_between[array_keys($points)[0]]+=($points[array_keys($points)[0]]*100);

                                    if( !isset($scores_between[array_keys($points)[1]]) ):
                                        $scores_between[array_keys($points)[1]] = 0;
                                    endif;

                                    //Tolgo 1000 punti per il set perso
                                    $scores_between[array_keys($points)[1]]-=1000;
                                    //Aggiungo 100 punti * games vinti
                                    $scores_between[array_keys($points)[1]]+=($points[array_keys($points)[1]]*100);

                                elseif( $points[array_keys($points)[1]] > $points[array_keys($points)[0]] ):
                                    if( !isset($scores_between[array_keys($points)[1]]) ):
                                        $scores_between[array_keys($points)[1]] = 0;
                                    endif;
                                    //Aggiungo un punto per un set vinto
                                    $scores_between[array_keys($points)[1]]+=1000;
                                    //Aggiungo la differenza di games vinti
                                    $scores_between[array_keys($points)[1]]+=($points[array_keys($points)[1]]*100);

                                    if( !isset($scores_between[array_keys($points)[0]]) ):
                                        $scores_between[array_keys($points)[0]] = 0;
                                    endif;
                                    //Tolgo un punto per il set perso
                                    $scores_between[array_keys($points)[0]]-=1000;
                                    //Tolgo la differenza di games persi
                                    $scores_between[array_keys($points)[0]]+=($points[array_keys($points)[0]]*100);
                                endif;
                            endforeach;
                        endforeach;

                        foreach($scores_between as $id_team => $sb):
                            foreach($classification as $c2):
                                if($c2['classification']['id_team'] == $id_team):
                                    $scores_between[$id_team] += ( intval($c2['classification']['games_won'])-intval($c2['classification']['games_lost']));
                                endif;
                            endforeach;
                        endforeach;

                        //dump($classification);
                        asort($scores_between);

                        //Ordine corretto della classifica
                        $new_classification = [];
                        $removed_k = [];

                        foreach($classification as $k => $c3):
                            if( in_array($c3['classification']['id_team'], $teams_id) ):
                                $new_classification [$c3['classification']['id_team']] = $c3;
                                $removed_k[] = $k;
                            endif;
                        endforeach;

                        asort($removed_k);

                        foreach($scores_between as $id_team => $sb):
                            $classification[array_pop($removed_k)] = $new_classification[$id_team];
                        endforeach;

                        $i = $i + 3;

                    endif;
                    */
                endif;
            endforeach;

            $rounds = Round::where('id_group', '=', $id_group)->orderBy('name', 'ASC')->get();


        endif;

        //dd($classification);

        $scores = [];
        $matchPlayers = [];

        if($rounds):
            foreach($rounds as $round){
                if( $round->matches ):
                    foreach($round->matches as $match){



                        $players_team1 = MatchPlayer::where('id_match', '=', $match->id)
                                                    ->where('side', '=', 'team1')
                                                    ->get();

                        foreach($players_team1 as $matchPlayer){
                            $matchPlayers[$match->id]['team1'][] = $matchPlayer->player;
                        }



                        $players_team2 = MatchPlayer::where('id_match', '=', $match->id)
                                                    ->where('side', '=', 'team2')
                                                    ->get();

                        foreach($players_team2 as $matchPlayer){
                            $matchPlayers[$match->id]['team2'][] = $matchPlayer->player;
                        }

                        foreach($match->scores as $score){

                            if($score->id_team == $match->id_team1):
                                $scores[$match->id][$score->set]['team1'] = $score->points;
                            else:
                                $scores[$match->id][$score->set]['team2'] = $score->points;
                            endif;

                        }
                    }
                endif;
            }
        endif;

        $subscribed = false;
        $subscriptions = [];

        $can_subscribe = false;
        if( $tournament->registration_deadline_date >= Carbon::now() ){
            $can_subscribe = true;
        }

        $tournament_started = false;
        if( $tournament->registration_deadline_date <= Carbon::now() ):
            $tournament_started = true;
        endif;


        $logged_in = ( Auth::id() > 0 );

        $subscriptions = Subscription::where('id_tournament', '=', $id_tournament)->get();
        if( $subscriptions ):
            foreach($subscriptions as $subscription):
                if( $subscription->teams ):
                    foreach($subscription->teams as $team):
                        foreach($team->players as $teamPlayer):
                            if(Auth::id() && $teamPlayer->id_player == Auth::id()):
                                $subscribed = true;
                                break;
                            endif;
                        endforeach;
                    endforeach;
                endif;
            endforeach;

            $subscriptions = Subscription::where('id_tournament', '=', $id_tournament)
                                            ->where('id_zone', '=', $id_zone)
                                            ->where('id_category_type', '=', $id_category_type)
                                            ->get();
        endif;


        $players = User::where('status', '=', 1)
                        ->where('id_role', '=', 2)
                        ->get();

        $images = $this->images($tournament->id_edition);

        //dd($can_subscribe, $tournament_started, $tournament);

        $utente_corrente = User::where('id', '=', Auth::id())->first();

        $zone = Zone::where('id', '=', $id_zone)->first();

        $categoryTypes = [];
        $id_categoryTypes = [];

        $divisions = Division::where('id_tournament', '=', $id_tournament)
                                ->where('id_zone', '=', $id_zone)
                                ->orderBy('id_category_type', 'asc')
                                ->orderBy('id_category', 'asc')
                                ->get();

        foreach( $divisions as $division ){

            if( !in_array($division->id_category_type, $id_categoryTypes) ){
                $id_categoryTypes[] = $division->id_category_type;
                $categoryTypes[] = CategoryType::where('id', '=', $division->id_category_type)->first();
            }
        }

        $categories = [];
        $id_categories = [];

        $divisions = Division::where('id_tournament', '=', $id_tournament)
                                ->where('id_zone', '=', $id_zone)
                                ->where('id_category_type', '=', $id_category_type)
                                ->orderBy('id_category_type', 'asc')
                                ->orderBy('id_category', 'asc')
                                ->get();

        foreach( $divisions as $division ){
            if( !in_array($division->id_category, $id_categories) ){
                $id_categories[] = $division->id_category;
                $categories[] = Category::where('id', '=', $division->id_category)->first();
            }
        }

        /** CANCELLA DA QUI E TOGLI I COMMENTI SOTTO */
        $zone_clubs = [];

        foreach( $tournament->edition->zones as $editionZone ):

            if( !isset($zone_clubs [ $editionZone->zone->city->name . ' ' . $editionZone->zone->name ]) ):
                $zone_clubs [ $editionZone->zone->city->name . ' ' . $editionZone->zone->name ] = [];
            endif;

            foreach( $editionZone->zone->clubs as $club ):
                $arr_club = array('id' => $club->club->id, 'name' => $club->club->name , 'address' => $club->club->address);
                $zone_clubs [ $editionZone->zone->city->name . ' ' . $editionZone->zone->name ] [] = $arr_club;;
            endforeach;

        endforeach;

        return view('page-torneo')
                ->with('tournament', $tournament)
                ->with('fase_a_gironi', $tournament)
                ->with('id_zone', $id_zone)
                ->with('zone', $zone)
                ->with('categoryTypes', $categoryTypes)
                ->with('categories', $categories)
                ->with('id_category_type', $id_category_type)
                ->with('groups', $groups)
                ->with('id_category', $id_category)
                ->with('classification', $classification)
                ->with('rounds', $rounds)
                ->with('scores', $scores)
                ->with('group', $group)
                ->with('id_group', $id_group)
                ->with('subscriptions', $subscriptions)
                ->with('subscribed', $subscribed)
                ->with('logged_in', $logged_in)
                ->with('can_subscribe', $can_subscribe)
                ->with('tournament_started', $tournament_started)
                ->with('id_bracket', $id_bracket)
                ->with('bracket', $bracket)
                ->with('players', $players)
                ->with('images', $images)
                ->with('zone_clubs', $zone_clubs)
                ->with('match_players', $matchPlayers)
                ->with('brackets', $brackets)
                ;
    }

    public function showGroupMacro($id_tournament, $id_zone, $id_category_type, $id_category, $id_group, $id_round){
        $tournament = Tournament::where('id', '=', $id_tournament)->first();
        $tournament['srcImgFeaturedBig'] = asset('storage/'.$tournament->edition->logo);
        $tournament['srcImgFeaturedBigx2'] = asset('storage/'.$tournament->edition->logo);

        /* Verifica se il torneo è iniziato */
        $tournament_started = false;
        if( $tournament->registration_deadline_date <= Carbon::now() ):
            $tournament_started = true;
        endif;

        /* Circoli delle zone */
        $zone_clubs = [];

        foreach( $tournament->edition->zones as $editionZone ):
            if( !isset($zone_clubs [ $editionZone->zone->city->name . ' ' . $editionZone->zone->name ]) ):
                $zone_clubs [ $editionZone->zone->city->name . ' ' . $editionZone->zone->name ] = [];
            endif;

            foreach( $editionZone->zone->clubs as $club ):
                $arr_club = array('id' => $club->club->id, 'name' => $club->club->name , 'address' => $club->club->address);
                $zone_clubs [ $editionZone->zone->city->name . ' ' . $editionZone->zone->name ] [] = $arr_club;;
            endforeach;
        endforeach;

        /* Zona Corrente */
        $zone = Zone::where('id', '=', $id_zone)->first();

        /* Tipologie */
        $categoryTypes = [];
        $id_categoryTypes = [];

        $divisions = Division::where('id_tournament', '=', $id_tournament)
                                ->where('id_zone', '=', $id_zone)
                                ->orderBy('id_category_type', 'asc')
                                ->orderBy('id_category', 'asc')
                                ->get();

        foreach( $divisions as $division ){

            if( !in_array($division->id_category_type, $id_categoryTypes) ){
                $id_categoryTypes[] = $division->id_category_type;
                $categoryTypes[] = CategoryType::where('id', '=', $division->id_category_type)->first();
            }
        }

        /* Categorie */
        $categories = [];
        $id_categories = [];

        $divisions = Division::where('id_tournament', '=', $id_tournament)
                                ->where('id_zone', '=', $id_zone)
                                ->where('id_category_type', '=', $id_category_type)
                                ->orderBy('id_category_type', 'asc')
                                ->orderBy('id_category', 'asc')
                                ->get();

        foreach( $divisions as $division ){
            if( !in_array($division->id_category, $id_categories) ){
                $id_categories[] = $division->id_category;
                $categories[] = Category::where('id', '=', $division->id_category)->first();
            }
        }

        /* Gironi */
        $division = Division::where('id_tournament', '=', $tournament->id)
                                ->where('id_zone', '=', $id_zone)
                                ->where('id_category_type', '=', $id_category_type)
                                ->where('id_category', '=', $id_category)
                                ->first();

        if($division):
            $groups = Group::where('id_division', '=', $division->id)->where('flag_online', '=', 1)->get();
            if( $id_category > 0 && $id_group == 0 && count($groups) > 0 ):
                $id_group = $groups[0]->id;
            endif;
        else:
            $groups = null;
        endif;

        /* Elenco tabelloni */
        $tabelloni = Tournament::where('id_tournament_type', '=', 2)
                                ->where('id_edition', '=', $tournament->id_edition)
                                ->where('id', '>', $id_tournament)
                                ->get();

        $bracket = null;
        $id_bracket = null;

        $brackets = [];
        foreach($tabelloni as $tabellone):
            $b = Bracket::where('id_tournament', '=', $tabellone->id)
                                        ->where('id_zone', '=', $id_zone)
                                        ->where('id_category_type', '=', $id_category_type)
                                        ->where('id_category', '=', $id_category)
                                        ->where('flag_online', '=', 1)
                                        ->first();

            $brackets[] = $b;
        endforeach;


        /* Classifica girone corrente */
        $group = Group::where('id', '=' ,$id_group)->where('flag_online', '=', 1)->first();

        $rounds = null;
        $classification = null;

        if($group):

            $res_classification = Group::selectRaw('gt.id_team, ifnull(sum(games_won), 0) - ifnull(sum(games_lost), 0) as points, ifnull(count(distinct id_match), 0) as played, ifnull(sum(won), 0) as won, ifnull(sum(lost), 0) as lost, ifnull(sum(draws), 0) as draws, ifnull(sum(set_won), 0) as set_won, ifnull(sum(set_lost), 0) as set_lost, ifnull(sum(games_won), 0) as games_won, ifnull(sum(games_lost), 0) as games_lost')
                                    ->leftjoin('group_macro_teams as gt', 'groups.id', '=', 'gt.id_group')
                                    ->leftJoin('macro_classifications as c', function($join){
                                        $join->on('gt.id_group', '=', 'c.id_group')
                                             ->on('gt.id_team', '=', 'c.id_team');
                                    })
                                    ->where('groups.id', '=', $id_group)
                                    ->groupBy('gt.id_team')
                                    ->orderByRaw('ifnull(sum(games_won), 0) - ifnull(sum(games_lost), 0) DESC, ifnull(sum(won), 0) - ifnull(sum(lost), 0) DESC, ifnull(sum(set_won), 0) - ifnull(sum(set_lost), 0) DESC')
                                    //->toSql()
                                    ->get()
                                    ;

            $classification = [];
            $classifications_points = [];

            foreach($res_classification as $i => $c){
                $team = MacroTeam::where('id', '=', $c->id_team)->first();
                $classification[$i]['team'] = $team;

                $classification[$i]['classification'] = $c->toArray();

                if( !isset($classifications_points[$c->points]) )
                    $classifications_points[$c->points] = 1;
                else
                    $classifications_points[$c->points]++;
            }

            //dd($classifications_points);
            //dd($classification);

            /* Calcolo scontri diretti */
            foreach($classification as $i => $c):
                if( $i < count($classification)-1):

                    /* Calcolo scontro diretto solo se sono 2 squadre a pari punti */
                    if( $classifications_points[ $c['classification']['points'] ] == 2 ):

                        if( $c['classification']['points'] == $classification[$i+1]['classification']['points'] && isset($group->id)):

                            //dd($c, $classification[$i+1]);
                            $risultato = Classification::where('id_group', $group->id)
                                                        ->where(function ($query) use ($c, $classification, $i) {
                                                            $query->where('id_team', '=', $c['classification']['id_team'])
                                                                  ->orWhere('id_team', '=', $classification[$i+1]['classification']['id_team']);
                                                        })->get();

                            if( $risultato ):

                                $ris = [];
                                foreach($risultato as $r):
                                    if( empty($ris[$r->id_match]['teams']) )
                                        $ris[$r->id_match]['teams'] = [];

                                    $ris[$r->id_match]['teams'][] = $r->id_team;

                                    if($r->points == 3){
                                        $ris[$r->id_match]['winner'] = $r->id_team;
                                    }
                                endforeach;

                                $winner = null;
                                foreach($ris as $r):
                                    if( count($r['teams']) == 2 )
                                        $winner = $r['winner'];
                                endforeach;

                                if($winner !== null):
                                    if( $c['classification']['id_team'] != $winner):
                                        //dd($c['classification']['id_team'] , $winner, $classification);
                                        $appo = $classification[$i];
                                        $classification[$i] = $classification[$i+1];
                                        $classification[$i+1] = $appo;
                                    endif;

                                endif;
                            endif;
                        endif;
                    endif;
                endif;
            endforeach;

            $rounds = Round::where('id_group', '=', $id_group)
                     ->orderByRaw('cast(name as unsigned) ASC')
                     ->get();

        endif;

        /* Calendario */

        $scores = [];
        $matchPlayers = [];
        $macro_matches = [];
        $allMacroScores = [];

        if($rounds):
            foreach($rounds as $round){
                if( $round->macro_matches ):
                    foreach($round->macro_matches as $macroMatch){

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

                            if( isset($match->id_team1) ):

                                $match_players = MatchPlayer::where('id_match', '=', $match->id)
                                                            ->where('id_team', '=', $match->id_team1)
                                                            ->get();
                                foreach($match_players as $matchPlayer){
                                    $matchPlayers[$match->id]['team1'][] = $matchPlayer->player;
                                }
                            endif;

                            if( isset($match->id_team2) ):
                                $match_players = MatchPlayer::where('id_match', '=', $match->id)
                                                            ->where('id_team', '=', $match->id_team2)
                                                            ->get();

                                foreach($match_players as $matchPlayer){
                                    $matchPlayers[$match->id]['team2'][] = $matchPlayer->player;
                                }
                            endif;

                            foreach($match->scores as $score){

                                if($score->id_team == $match->id_team1):
                                    $scores[$match->id][$score->set]['team1'] = $score->points;
                                else:
                                    $scores[$match->id][$score->set]['team2'] = $score->points;
                                endif;

                            }

                        endforeach;
                    }
                endif;
            }
        endif;

        switch($id_round){
            case 0: {
                if(!empty($rounds)){
                    $sel_round = $rounds[0];
                }else{
                    $sel_round = 0;
                }
                break;
            }
            default: {
                $sel_round = Round::find($id_round);
                break;
            }
        }

        $subscriptions = MacroSubscription::where('id_tournament', '=', $tournament->id)
                                            ->where('id_category_type', '=', $id_category_type)
                                            ->get();

        //dd($matchPlayers);

        return view('page-torneo-squadre')
                    ->with('tournament', $tournament)
                    ->with('fase_a_gironi', $tournament)
                    ->with('subscriptions', $subscriptions)
                    ->with('tournament_started', $tournament_started)
                    ->with('id_zone', $id_zone)
                    ->with('id_category_type', $id_category_type)
                    ->with('id_category', $id_category)
                    ->with('id_group', $id_group)
                    ->with('zone_clubs', $zone_clubs)
                    ->with('zone', $zone)
                    ->with('categoryTypes', $categoryTypes)
                    ->with('categories', $categories)
                    ->with('groups', $groups)
                    ->with('brackets', $brackets)
                    ->with('classification', $classification)
                    ->with('rounds', $rounds)
                    ->with('scores', $scores)
                    ->with('macro_scores', $allMacroScores)
                    ->with('match_players', $matchPlayers)
                    ->with('sel_round', $sel_round)
                    ;
    }

    public function showSubscribed($id_tournament, $id_zone, $id_category_type){
        $tournament = Tournament::where('id', '=', $id_tournament)->first();
        if ($tournament->id_tournament_type == 1){
            $subscriptions = Subscription::where('id_tournament', '=', $id_tournament)
            ->where('id_zone', '=', $id_zone)
            ->where('id_category_type', '=', $id_category_type)
            ->get();
        }elseif($tournament->id_tournament_type == 2){
            $bracket = Bracket::where('id_tournament', '=', $id_tournament)
            ->where('id_zone', '=', $id_zone)
            ->where('id_category_type', '=', $id_category_type)
            ->where('id_category', '=', $id_category)
            ->where('flag_online', '=', 1)
            ->first();
            if($bracket):
                $id_bracket = $bracket->id;
            else:
                $id_bracket = null;
            endif;

            $phases = Phase::where('id_bracket', '=' ,$id_bracket)->orderBy('name', 'ASC')->get();

            $scores = [];
            $rounds = [];
            $subscriptions = [];

            if($phases):
                foreach($phases as $phase):

                    if( $phase->name == 1 ):
                        if( $phase->teams ):
                            foreach( $phase->teams as $phaseTeam ):
                                $subscriptions[] = $phaseTeam;
                            endforeach;
                        endif;
                    endif;
                endforeach;
            endif;
        }
        return view('list_subscriptions')

        ->with('tournament', $tournament)
        ->with('subscriptions', $subscriptions)
        ->with('id_zone', $id_zone)
        ->with('id_category_type', $id_category_type)
        ->with('id_tournament', $id_tournament)
        ;

    }


    public function showBracket($id_tournament, $id_zone, $id_category_type, $id_category, $id_bracket, $id_phase){

        $tournament = Tournament::where('id', '=', $id_tournament)->first();
        $edition = $tournament->edition;

        if( in_array($edition->edition_type, [0,1]) ):
            return $this->showBracketDouble($id_tournament, $id_zone, $id_category_type, $id_category, $id_bracket, $id_phase);
        elseif( $edition->edition_type == 2 ):
            return $this->showBracketMacro($id_tournament, $id_zone, $id_category_type, $id_category, $id_bracket, $id_phase);
        endif;

    }

    public function showBracketDouble($id_tournament, $id_zone, $id_category_type, $id_category, $id_bracket, $id_phase){

        $tournament = Tournament::where('id', '=', $id_tournament)->first();
        $tournament['srcImgFeaturedBig'] = asset('storage/'.$tournament->edition->logo);
        $tournament['srcImgFeaturedBigx2'] = asset('storage/'.$tournament->edition->logo);

        $edition = $tournament->edition;

        $arr_phases = [];
        $arr_images = [];
        $groups = null;
        $phases = null;
        $phase = null;

        /* */
        $tabelloni = Tournament::where('id_tournament_type', '=', 2)
                                ->where('id_edition', '=', $tournament->id_edition)
                                ->get();

        $brackets = [];
        foreach($tabelloni as $tabellone):
            $b = Bracket::where('id_tournament', '=', $tabellone->id)
                        ->where('id_zone', '=', $id_zone)
                        ->where('id_category_type', '=', $id_category_type)
                        ->where('id_category', '=', $id_category)
                        ->where('flag_online', '=', 1)
                        ->first();

            $brackets[] = $b;
        endforeach;

        /* Recupero i gironi */
        $fase_a_gironi = Tournament::where('id_tournament_type', '=', 1)
                                        ->where('id_edition', '=', $tournament->id_edition)
                                        ->where('id', '<', $id_tournament)
                                        ->first();

        $subscriptions = Subscription::where('id_tournament', '=', $fase_a_gironi->id)->get();
        if( $subscriptions ):
            foreach($subscriptions as $subscription):
                if( $subscription->teams ):
                    foreach($subscription->teams as $team):
                        foreach($team->players as $teamPlayer):
                            if(Auth::id() && $teamPlayer->id_player == Auth::id()):
                                $subscribed = true;
                                break;
                            endif;
                        endforeach;
                    endforeach;
                endif;
            endforeach;

            $subscriptions = Subscription::where('id_tournament', '=', $fase_a_gironi->id)
                                            ->where('id_zone', '=', $id_zone)
                                            ->where('id_category_type', '=', $id_category_type)
                                            ->get();
        endif;

        $division = Division::where('id_tournament', '=', $fase_a_gironi->id)
                        ->where('id_zone', '=', $id_zone)
                        ->where('id_category_type', '=', $id_category_type)
                        ->where('id_category', '=', $id_category)
                        ->first();

        if($division):
            $groups = Group::where('id_division', '=', $division->id)->get();
        endif;

        $zone = Zone::where('id', '=', $id_zone)->first();

        $categoryTypes = [];
        $categories = [];

        $id_categoryTypes = [];
        $id_categories = [];


        $divisions = Division::where('id_tournament', $fase_a_gironi->id)
                                ->where('id_zone', '=', $id_zone)
                                ->orderBy('id_category_type', 'asc')
                                ->orderBy('id_category', 'asc')
                                ->get();

        foreach( $divisions as $division ){

            if( !in_array($division->id_category_type, $id_categoryTypes) ){
                $id_categoryTypes[] = $division->id_category_type;
                $categoryTypes[] = CategoryType::where('id', '=', $division->id_category_type)->first();
            }

            if( !in_array($division->id_category, $id_categories) ){
                $id_categories[] = $division->id_category;
                $categories[] = Category::where('id', '=', $division->id_category)->first();
            }
        }

        $bracket = Bracket::where('id_tournament', '=', $id_tournament)
                                ->where('id_zone', '=', $id_zone)
                                ->where('id_category_type', '=', $id_category_type)
                                ->where('id_category', '=', $id_category)
                                ->first();

        if($bracket):
            $id_bracket = $bracket->id;


            if($id_phase==0):
                $id_phase = Phase::where('id_bracket', '=', $bracket->id)->orderBy('name', 'ASC')->first()->id;
            endif;

            $phase = Phase::where('id', '=', $id_phase)->first();

            $phases = Phase::where('id_bracket', '=', $bracket->id)->get();
            foreach($phases as $iphase):

                switch( count($iphase->matches) ) {
                    case 16: $arr_phases[$iphase->id]['name'] = 'Sedicesimi'; break;
                    case  8: $arr_phases[$iphase->id]['name'] = 'Ottavi'; break;
                    case  4: $arr_phases[$iphase->id]['name'] = 'Quarti'; break;
                    case  2: $arr_phases[$iphase->id]['name'] = 'Semifinali'; break;
                    case  1: $arr_phases[$iphase->id]['name'] = 'Finale'; break;
                }

            endforeach;

            //dd($arr_phases);

        else:
            $id_bracket = null;
        endif;


        $scores = [];
        $rounds = [];
        $subscriptions = [];

        $matchPlayers = [];

        if($phases):
            foreach($phases as $iphase):

                if( $iphase->name == 1 ):
                    if( $iphase->teams ):
                        foreach( $iphase->teams as $phaseTeam ):
                            $subscriptions[] = $phaseTeam;
                        endforeach;
                    endif;
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

                        foreach($match->scores as $score){

                            if($score->id_team == $match->id_team1):
                                $scores[$match->id][$score->set]['team1'] = $score->points;
                            else:
                                $scores[$match->id][$score->set]['team2'] = $score->points;
                            endif;

                        }

                    endforeach;
                endif;
            endforeach;
        endif;

        $logged_in = false;
        $subscribed = false;
        $classification = [];
        $group = null;

        $can_subscribe = false;

        $tournament_started = true; //di default
        /*
        if( $tournament->date_start <= Carbon::now() ):
            $tournament_started = true;
        endif;
         *
         */

        $players = User::where('status', '=', 1)
                        ->where('id_role', '=', 2)
                        ->get();



        $images = $this->images($tournament->id_edition);

        $zone = Zone::where('id', '=', $id_zone)->first();

        /* Circoli della zona corrente */
        $zone_clubs = [];

        foreach( $tournament->edition->zones as $editionZone ):

            if( !isset($zone_clubs [ $editionZone->zone->city->name . ' ' . $editionZone->zone->name ]) ):
                $zone_clubs [ $editionZone->zone->city->name . ' ' . $editionZone->zone->name ] = [];
            endif;

            foreach( $editionZone->zone->clubs as $club ):
                $arr_club = array('id' => $club->club->id, 'name' => $club->club->name , 'address' => $club->club->address);
                $zone_clubs [ $editionZone->zone->city->name . ' ' . $editionZone->zone->name ] [] = $arr_club;;
            endforeach;

        endforeach;

        //dd($groups->toArray());
        $matches = [];

        if( isset($phase->matches) && !empty($phase->matches) ):
            foreach($phase->matches as $match):
                if( intval($phase->name) == 1 && ( !empty($match->id_team1) || !empty($match->id_team2) )):
                    $matches[$phase->name][$match->next_matchcode] = $match;
                else:
                    $matches[$phase->name][$match->next_matchcode] = $match;
                endif;
            endforeach;
        endif;


        $categoryTypes = [];
        $categories = [];

        $id_categoryTypes = [];
        $id_categories = [];


        $arr_brackets = Bracket::where('id_zone', '=', $id_zone)
                                ->orderBy('id_category_type', 'asc')
                                ->orderBy('id_category', 'asc')
                                ->get();

        foreach( $arr_brackets as $b ){

            if( !in_array($b->id_category_type, $id_categoryTypes) ){
                $id_categoryTypes[] = $b->id_category_type;
                $categoryTypes[] = CategoryType::where('id', '=', $b->id_category_type)->first();
            }
        }

        $arr_brackets = Bracket::where('id_tournament', $tabelloni[0]->id)
                                ->where('id_zone', '=', $id_zone)
                                ->where('id_category_type', '=', $id_category_type)
                                ->where('generated', 1)
                                ->where('flag_online', 1)
                                ->orderBy('id_category_type', 'asc')
                                ->orderBy('id_category', 'asc')
                                ->get();

        foreach( $arr_brackets as $b ){
            if( !in_array($b->id_category, $id_categories) ){
                $id_categories[] = $b->id_category;
                $categories[] = Category::where('id', '=', $b->id_category)->first();
            }
        }

        return view('page-torneo')
                 ->with('tournament', $tournament)
                 ->with('id_zone', $id_zone)
                 ->with('categoryTypes', $categoryTypes)
                 ->with('categories', $categories)
                 ->with('id_category_type', $id_category_type)
                 ->with('groups', $groups)
                 ->with('id_category', $id_category)
                 ->with('classification', $classification)
                 ->with('rounds', $rounds)
                 ->with('scores', $scores)
                 ->with('group', $group)
                 ->with('subscriptions', $subscriptions)
                 ->with('subscribed', $subscribed)
                 ->with('logged_in', Auth::check())
                 ->with('can_subscribe', $can_subscribe)
                 ->with('id_bracket', $id_bracket)
                 ->with('bracket', $bracket)
                 ->with('brackets', $brackets)
                 ->with('players', $players)
                 ->with('images', $images)
                 ->with('tournament_started', $tournament_started)
                 ->with('fase_a_gironi', $fase_a_gironi)
                 ->with('arr_phases', $arr_phases)
                 ->with('id_phase', $id_phase)
                 ->with('phase', $phase)
                 ->with('zone', $zone)
                 ->with('zone_clubs', $zone_clubs)
                 ->with('matches', $matches)
                 ->with('match_players', $matchPlayers)
                 ;

    }

    public function showBracketMacro($id_tournament, $id_zone, $id_category_type, $id_category, $id_bracket, $id_phase){

        $tournament = Tournament::where('id', '=', $id_tournament)->first();
        $tournament['srcImgFeaturedBig'] = asset('storage/'.$tournament->edition->logo);
        $tournament['srcImgFeaturedBigx2'] = asset('storage/'.$tournament->edition->logo);

        $edition = $tournament->edition;

        $arr_phases = [];
        $arr_images = [];
        $groups = null;
        $phases = null;
        $phase = null;

        /* */
        $tabelloni = Tournament::where('id_tournament_type', '=', 2)
                                ->where('id_edition', '=', $tournament->id_edition)
                                ->get();

        $brackets = [];
        foreach($tabelloni as $tabellone):
            $b = Bracket::where('id_tournament', '=', $tabellone->id)
                        ->where('id_zone', '=', $id_zone)
                        ->where('id_category_type', '=', $id_category_type)
                        ->where('id_category', '=', $id_category)
                        ->where('flag_online', '=', 1)
                        ->first();

            $brackets[] = $b;
        endforeach;

        /* Recupero i gironi */
        $fase_a_gironi = Tournament::where('id_tournament_type', '=', 1)
                                        ->where('id_edition', '=', $tournament->id_edition)
                                        ->where('id', '<', $id_tournament)
                                        ->first();


        $subscriptions = MacroSubscription::where('id_tournament', '=', $fase_a_gironi->id)->get();
        if( $subscriptions ):
            foreach($subscriptions as $subscription):
                if( $subscription->teams ):
                    foreach($subscription->teams as $team):
                        foreach($team->players as $teamPlayer):
                            if(Auth::id() && $teamPlayer->id_player == Auth::id()):
                                $subscribed = true;
                                break;
                            endif;
                        endforeach;
                    endforeach;
                endif;
            endforeach;

            $subscriptions = MacroSubscription::where('id_tournament', '=', $fase_a_gironi->id)
                                                ->where('id_category_type', '=', $id_category_type)
                                                ->get();
        endif;


        $division = Division::where('id_tournament', '=', $fase_a_gironi->id)
                        ->where('id_zone', '=', $id_zone)
                        ->where('id_category_type', '=', $id_category_type)
                        ->where('id_category', '=', $id_category)
                        ->first();

        if($division):
            $groups = Group::where('id_division', '=', $division->id)->get();
        endif;

        $zone = Zone::where('id', '=', $id_zone)->first();

        $categoryTypes = [];
        $categories = [];

        $id_categoryTypes = [];
        $id_categories = [];


        $divisions = Division::where('id_zone', '=', $id_zone)
                                ->orderBy('id_category_type', 'asc')
                                ->orderBy('id_category', 'asc')
                                ->get();

        foreach( $divisions as $division ){

            if( !in_array($division->id_category_type, $id_categoryTypes) ){
                $id_categoryTypes[] = $division->id_category_type;
                $categoryTypes[] = CategoryType::where('id', '=', $division->id_category_type)->first();
            }

            if( !in_array($division->id_category, $id_categories) ){
                $id_categories[] = $division->id_category;
                $categories[] = Category::where('id', '=', $division->id_category)->first();
            }
        }

        $bracket = Bracket::where('id_tournament', '=', $id_tournament)
                                ->where('id_zone', '=', $id_zone)
                                ->where('id_category_type', '=', $id_category_type)
                                ->where('id_category', '=', $id_category)
                                ->first();

        if($bracket):
            $id_bracket = $bracket->id;


            if($id_phase==0):
                $id_phase = Phase::where('id_bracket', '=', $bracket->id)->orderBy('name', 'ASC')->first()->id;
            endif;

            $phase = Phase::where('id', '=', $id_phase)->first();

            $phases = Phase::where('id_bracket', '=', $bracket->id)->get();
            foreach($phases as $iphase):

                switch( count($iphase->macro_matches) ) {
                    case 16: $arr_phases[$iphase->id]['name'] = 'Sedicesimi'; break;
                    case  8: $arr_phases[$iphase->id]['name'] = 'Ottavi'; break;
                    case  4: $arr_phases[$iphase->id]['name'] = 'Quarti'; break;
                    case  2: $arr_phases[$iphase->id]['name'] = 'Semifinali'; break;
                    case  1: $arr_phases[$iphase->id]['name'] = 'Finale'; break;
                }

            endforeach;

            //dd($arr_phases);

        else:
            $id_bracket = null;
        endif;


        $macroScores = [];
        $rounds = [];

        $matchPlayers = [];


        if($phases):
            foreach($phases as $iphase):

                if( $iphase->name == 1 ):
                    if( $iphase->teams ):
                        foreach( $iphase->teams as $phaseTeam ):
                            $subscriptions[] = $phaseTeam;
                        endforeach;
                    endif;
                endif;

                if( $iphase->macro_matches ):
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

                        foreach($macroMatch->macro_scores as $score){
                            if($score->id_team == $macroMatch->id_team1){
                                if(!isset($macroScores[$macroMatch->id]['team1'])){
                                    $macroScores[$macroMatch->id]['team1'] = 0;
                                }
                                $macroScores[$macroMatch->id]['team1'] += $score->points;
                            }else{
                                if(!isset($macroScores[$macroMatch->id]['team2'])){
                                    $macroScores[$macroMatch->id]['team2'] = 0;
                                }
                                $macroScores[$macroMatch->id]['team2'] += $score->points;
                            }
                        }

                    endforeach;
                endif;
            endforeach;
        endif;


        $logged_in = false;
        if( Auth::id() ):
            $logged_in = true;
        endif;

        $subscribed = false;
        $classification = [];
        $group = null;

        $can_subscribe = false;

        $tournament_started = true; //di default
        /*
        if( $tournament->date_start <= Carbon::now() ):
            $tournament_started = true;
        endif;
         *
         */


        $players = User::where('status', '=', 1)
                        ->where('id_role', '=', 2)
                        ->get();

        $images = $this->images($tournament->id_edition);

        $zone = Zone::where('id', '=', $id_zone)->first();

        /* Circoli della zona corrente */
        $zone_clubs = [];

        foreach( $tournament->edition->zones as $editionZone ):

            if( !isset($zone_clubs [ $editionZone->zone->city->name . ' ' . $editionZone->zone->name ]) ):
                $zone_clubs [ $editionZone->zone->city->name . ' ' . $editionZone->zone->name ] = [];
            endif;

            foreach( $editionZone->zone->clubs as $club ):
                $arr_club = array('id' => $club->club->id, 'name' => $club->club->name , 'address' => $club->club->address);
                $zone_clubs [ $editionZone->zone->city->name . ' ' . $editionZone->zone->name ] [] = $arr_club;;
            endforeach;

        endforeach;

        //dd($groups->toArray());
        $macro_matches = [];
        $scores = [];

        if( isset($phase->macro_matches) && !empty($phase->macro_matches) ):
            foreach($phase->macro_matches as $macroMatch):
                $macroMatch->load('submatches.team1.players');
                $macroMatch->load('submatches.team2.players');
                $macroMatch->load('submatches.scores');
                $macroMatch->load('submatches.matchPlayers.player');

                if( intval($phase->name) == 1 && ( !empty($macroMatch->id_team1) || !empty($macroMatch->id_team2) )):
                    $macro_matches[$phase->name][$macroMatch->next_matchcode] = $macroMatch;
                else:
                    $macro_matches[$phase->name][$macroMatch->next_matchcode] = $macroMatch;
                endif;

                foreach($macroMatch->submatches as $match){
                    foreach($match->scores as $score){

                        if($score->id_team == $match->id_team1):
                            $scores[$match->id][$score->set]['team1'] = $score->points;
                        else:
                            $scores[$match->id][$score->set]['team2'] = $score->points;
                        endif;

                    }
                }

            endforeach;
        endif;


        $categoryTypes = [];
        $categories = [];

        $id_categoryTypes = [];
        $id_categories = [];


        $arr_brackets = Bracket::where('id_tournament', '=', $id_tournament)
                                ->where('id_zone', '=', $id_zone)
                                ->orderBy('id_category_type', 'asc')
                                ->orderBy('id_category', 'asc')
                                ->get();

        foreach( $arr_brackets as $b ){

            if( !in_array($b->id_category_type, $id_categoryTypes) ){
                $id_categoryTypes[] = $b->id_category_type;
                $categoryTypes[] = CategoryType::where('id', '=', $b->id_category_type)->first();
            }
        }

        $arr_brackets = Bracket::where('id_tournament', '=', $id_tournament)
                                ->where('id_zone', '=', $id_zone)
                                ->where('id_category_type', '=', $id_category_type)
                                ->orderBy('id_category_type', 'asc')
                                ->orderBy('id_category', 'asc')
                                ->get();

        foreach( $arr_brackets as $b ){
            if( !in_array($b->id_category, $id_categories) ){
                $id_categories[] = $b->id_category;
                $categories[] = Category::where('id', '=', $b->id_category)->first();
            }
        }

        return view('page-torneo-squadre')
                 ->with('tournament', $tournament)
                 ->with('id_zone', $id_zone)
                 ->with('categoryTypes', $categoryTypes)
                 ->with('categories', $categories)
                 ->with('id_category_type', $id_category_type)
                 ->with('groups', $groups)
                 ->with('id_category', $id_category)
                 ->with('classification', $classification)
                 ->with('rounds', $rounds)
                 ->with('group', $group)
                 ->with('subscriptions', $subscriptions)
                 ->with('subscribed', $subscribed)
                 ->with('logged_in', $logged_in)
                 ->with('can_subscribe', $can_subscribe)
                 ->with('id_bracket', $id_bracket)
                 ->with('bracket', $bracket)
                 ->with('brackets', $brackets)
                 ->with('players', $players)
                 ->with('images', $images)
                 ->with('tournament_started', $tournament_started)
                 ->with('fase_a_gironi', $fase_a_gironi)
                 ->with('arr_phases', $arr_phases)
                 ->with('id_phase', $id_phase)
                 ->with('phase', $phase)
                 ->with('zone', $zone)
                 ->with('zone_clubs', $zone_clubs)
                 ->with('scores', $scores)
                 ->with('macro_matches', $macro_matches)
                 ->with('match_players', $matchPlayers)
                 ->with('macro_scores', $macroScores)
                 ;

    }

    private function images($id_edition){
        $tournaments = Tournament::where('id_edition', '=', $id_edition)->get();
        $arr_images = [];
        foreach($tournaments as $tournament):

            if( $tournament->id_tournament_type == 1 ):

                $divisions = Division::where('id_tournament', '=', $tournament->id)->get();
                foreach($divisions as $division):
                    $groups = Group::where('id_division', '=', $division->id)
                                    ->where('flag_online', '=', 1)
                                    ->get();
                    foreach($groups as $group):
                        $rounds = Round::where('id_group', '=', $group->id)
                                        ->get();

                        foreach($rounds as $round):
                            $matches = Match::where('matchcode', '=', $round->matchcode)->get();
                            foreach($matches as $match):
                                $images = Image::where('id_match', '=', $match->id)->get();
                                foreach($images as $image):
                                    $arr_images[$match->id][] = $image->path;
                                endforeach;
                            endforeach;
                        endforeach;
                    endforeach;
                endforeach;

            elseif( $tournament->id_tournament_type == 2 ):

                $brackets = Bracket::where('id_tournament', '=', $tournament->id)
                                    ->where('flag_online', '=', 1)
                                    ->get();

                foreach($brackets as $bracket):
                    $phases = Phase::where('id_bracket', '=', $bracket->id)->get();
                    foreach($phases as $phase):
                        $matches = Match::where('matchcode', '=', $phase->matchcode)->get();
                        foreach($matches as $match):
                            $images = Image::where('id_match', '=', $match->id)->get();
                            foreach($images as $image):
                                $arr_images[$match->id][] = $image->path;
                            endforeach;
                        endforeach;
                    endforeach;
                endforeach;

            endif;

        endforeach;

        /*
        $res = [];
        foreach($arr_images as $image):
            $res[] = url('storage/app/'.$image);
        endforeach;
        */
        return $arr_images;
    }

}
