<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\User;
use App\Models\TeamPlayer;
use App\Models\UserMetaItem;
use App\Models\UserClub;
use App\Models\Ranking;

use App\Models\Group;
use App\Models\Bracket;
use App\Models\Round;
use App\Models\Phase;
use App\Models\Tournament;
use App\Models\Division;
use App\Models\Match;
use App\Models\MatchPlayer;
use App\Models\Club;
use App\Models\City;
use App\Models\Partner;

class PlayerController extends Controller
{

    public function index(Request $request, $gender){
        $search = "";
        if($request->has('search')):
            $search = $request->input('search');
            $players = User::where('id_role', '=', 2)
                            ->where('status', '=', 1)
                            ->where('gender', '=', $gender)
                            ->where(function($query) use ($search){
                                $query->where('name', 'like', '%'.$search.'%');
                                $query->orWhere('surname', 'like', '%'.$search.'%');
                            })
                            ->paginate(20)->appends(request()->except('page'));
        else:

            $players = User::where('id_role', '=', 2)
                            ->where('status', '=', 1)
                            ->where('gender', '=', $gender)
                            ->paginate(20)->appends(request()->except('page'));

        endif;

        $posizioni_players = [];
        $stats = [];

        /*
        $rankings = Ranking::selectRaw('id_player, sum(points) as points, sum(match_won) match_won, sum(match_lost) match_lost, sum(set_won) set_won, sum(set_lost) set_lost, sum(games_won) games_won, sum(games_lost) games_lost')
                                ->where('date', '>', Carbon::now()->subYear(1))
                                ->groupBy('id_player')
                                ->get()
                                ;

        $stats = [];
        $posizioni = [];

        foreach($rankings as $ranking):

            $points = isset($ranking->points) && $ranking->points > 0 ? $ranking->points : 0;
            $match_won = isset($ranking->match_won) && $ranking->match_won > 0 ? $ranking->match_won : 0;
            $match_lost = isset($ranking->match_lost) && $ranking->match_lost > 0 ? $ranking->match_lost : 0;

            $stats[$ranking->id_player] = ["pt"=>$points,"pv"=>$match_won,"pp"=>$match_lost];

            if( !isset($posizioni[$ranking->points]) ) $posizioni[$ranking->points] = [];
            $posizioni[$ranking->points][] = $ranking->id_player;

        endforeach;

        $posizioni_players = [];
        foreach($posizioni as $pos => $id_players):
            foreach($id_players as $id_player):
                $posizioni_players[$id_player] = ($pos+1);
            endforeach;
        endforeach;
        
        $livelli = ["n.d.","principiante","esordiente","intermendio","avanzato","pro"];
        foreach($players as $player):
            $player->stats = ["pv"=>rand(1,100),"pp"=>rand(1,100),"livello"=>$livelli[rand(0,5)]];
        endforeach;
        */

        $cities = City::orderBy('name', 'ASC')->get();

        return view('archive-giocatori')
                        ->with('players', $players)
                        ->with('search',$search)
                        ->with('stats', $stats)
                        ->with('selected_gender', $gender)
                        ->with('cities', $cities)
                        ->with('selected_city', '')
                        ->with('selected_city_id', null)
                        ->with('posizioni', $posizioni_players)
                        ;
    }

    public function showByCity(Request $request, $gender, $slug_city){
        $search = "";

        $city_sel = City::where('slug', '=', $slug_city)->first();

        if($request->has('search')):
            $search = $request->input('search');
            $players = User::where('id_role', '=', 2)
                            ->where('status', '=', 1)
                            ->where('gender', '=', $gender)
                            ->where('id_city', '=', $city_sel->id)
                            ->where(function($query) use ($search){
                                $query->where('name', 'like', '%'.$search.'%');
                                $query->orWhere('surname', 'like', '%'.$search.'%');
                            })
                            ->paginate(20)->appends(request()->except('page'));
        else:

            $players = User::where('id_role', '=', 2)
                            ->where('status', '=', 1)
                            ->where('gender', '=', $gender)
                            ->where('id_city', '=', $city_sel->id)
                            ->paginate(20)->appends(request()->except('page'));

        endif;

        $rankings = Ranking::selectRaw('id_player, sum(points) as points, sum(match_won) match_won, sum(match_lost) match_lost, sum(set_won) set_won, sum(set_lost) set_lost, sum(games_won) games_won, sum(games_lost) games_lost')
                                ->where('date', '>', Carbon::now()->subYear(1))
                                ->groupBy('id_player')
                                ->get()
                                ;

        $stats = [];
        $posizioni = [];

        foreach($rankings as $ranking):

            $points = isset($ranking->points) && $ranking->points > 0 ? $ranking->points : 0;
            $match_won = isset($ranking->match_won) && $ranking->match_won > 0 ? $ranking->match_won : 0;
            $match_lost = isset($ranking->match_lost) && $ranking->match_lost > 0 ? $ranking->match_lost : 0;

            $stats[$ranking->id_player] = ["pt"=>$points,"pv"=>$match_won,"pp"=>$match_lost];

            if( !isset($posizioni[$ranking->points]) ) $posizioni[$ranking->points] = [];
            $posizioni[$ranking->points][] = $ranking->id_player;
        endforeach;

        /*
        $livelli = ["n.d.","principiante","esordiente","intermendio","avanzato","pro"];
        foreach($players as $player):
            $player->stats = ["pv"=>rand(1,100),"pp"=>rand(1,100),"livello"=>$livelli[rand(0,5)]];
        endforeach;
        */

        $posizioni_players = [];
        foreach($posizioni as $pos => $id_players):
            foreach($id_players as $id_player):
                $posizioni_players[$id_player] = ($pos+1);
            endforeach;
        endforeach;


        $cities = City::orderBy('name', 'ASC')->get();

        return view('archive-giocatori')
                        ->with('players', $players)
                        ->with('search',$search)
                        ->with('stats', $stats)
                        ->with('selected_gender', $gender)
                        ->with('cities', $cities)
                        ->with('selected_city', $slug_city)
                        ->with('selected_city_id', $city_sel->id)
                        ->with('posizioni', $posizioni_players)
                        ;
    }

    public function show($id_player){

        $user = User::where('id', '=', $id_player)
                        ->where('status', '=', 1)
                        ->first();


        if( $user->id_role == 2 ):

            $player = $user;

            $userMetaItems = UserMetaItem::where('meta_cat', '=', 'info')
                                            ->where('private', '=', 0)
                                            ->get()
                                            ->toArray();

            $meta_values = [];
            foreach($userMetaItems as $meta):

                if($meta['meta_type'] !== 'select'):
                    $meta_values[$meta['meta_key']] = $meta['meta_values'];
                else:
                    $meta_values[$meta['meta_key']] = explode(',',$meta['meta_values']);
                endif;

                $metaTypes[$meta['meta_key']] = $meta['meta_type'];
                $metaValues[$meta['meta_key']] = explode(",", $meta['meta_values']);
                $metaPrivate[$meta['meta_key']] = $meta['private'];

            endforeach;



            $arr_metas = [];
            $arr_private_metas = [];

            if( isset($player->metas) ):
                foreach($player->metas as $meta):

                    if( isset($metaTypes[$meta->meta]) || $meta->meta == 'avatar' || $meta->meta == 'data_nascita'){
                        if( $meta->meta !== 'data_nascita' ){
                            if( $meta->meta == 'avatar' || ( isset($metaPrivate[$meta->meta]) && $metaPrivate[$meta->meta] == 0 ) ){
                                $arr_metas[$meta->meta] = $meta->meta_value;
                            }else{
                                $arr_private_metas[$meta->meta] = $meta->meta_value;
                            }
                        }else{
                            if( $meta->meta == 'data_nascita' && !empty($meta->meta_value) ){
                                $dbDate = null;
                                $dataval = explode("-", $meta->meta_value);
                                if(count($dataval)==3 && strlen($dataval[0])==4):
                                    $dbDate = Carbon::createFromFormat('Y-m-d', $meta->meta_value);
                                else:
                                    $dataval = explode("/", $meta->meta_value);
                                    if(count($dataval)==3 && strlen($dataval[0]==2)):
                                        $dbDate = Carbon::createFromFormat('d/m/Y', $meta->meta_value);
                                    endif;
                                endif;

                                $metaValues['eta'] = '';
                                if($dbDate):
                                    $arr_metas['eta'] = Carbon::now()->diffInYears($dbDate);
                                endif;
                            }
                        }
                    }

                endforeach;
            endif;

            //dd($arr_metas, $arr_private_metas);


            $sliders = [];

            foreach($userMetaItems as $metaItem):
                if($metaItem['meta_type'] == 'slider'):
                    $found = false;
                    foreach($player->metas as $meta):
                        if($meta->meta == $metaItem['meta_key']):
                            $sliders[$meta->meta] = $meta->meta_value*10;
                            $found = true;
                        endif;
                    endforeach;

                    if(!$found):
                        $sliders[$metaItem['meta_key']] = 0;
                    endif;

                endif;
            endforeach;

            $clubs = [];
            $userClubs = UserClub::where('id_user', '=', $id_player)->get();
            foreach($userClubs as $userClub):
                $clubs[] = "<a href='/clubs/".$userClub->club->id."/show'>".$userClub->club->name."</a>";
            endforeach;


            /** STATS */

            /*
            $stats = [];
            $stats['eventi'] = 0;
            $stats['giocate'] = 0;
            $stats['vinte'] = 0;
            $stats['perse'] = 0;
            $stats['pareggi'] = 0;
            $stats['set_vinti'] = 0;
            $stats['set_persi'] = 0;
            $stats['games_vinti'] = 0;
            $stats['games_persi'] = 0;
            $stats['points'] = 0;

            $arr_tournaments = [];

            $rankings = Ranking::selectRaw('id_player, sum(points) as points')
                                    ->where('date', '>', Carbon::now()->subYear(1))
                                    ->groupBy('id_player')
                                    ->orderBy('points', 'desc')
                                    ->get()
                                    ;

            $pos = 1;
            $prev = null;
            $posizioni = [];
            foreach($rankings as $rank):
                if( !isset($posizioni[$rank->points]) ){
                    $posizioni[$rank->points] = [];
                }

                $posizioni[$rank->points][] = $rank->id_player;
            endforeach;

            $posizione = '-';
            $ranking_pos = '-';
            foreach($posizioni as $players){
                if($ranking_pos == '-') $ranking_pos = 0;
                $ranking_pos++;
                if( in_array($id_player, $players) ){
                    $posizione = $ranking_pos;
                    break;
                }
            }

            $rankings = Ranking::where('date', '>', Carbon::now()->subYear(1))
                                ->where('id_player', '=', $id_player)
                                ->get()
                                ;

            foreach($rankings as $ranking):
                $stats['giocate']++;
                $stats['vinte'] += $ranking->match_won;
                $stats['perse'] += $ranking->match_lost;
                $stats['pareggi'] += $ranking->match_deuce;
                $stats['set_vinti'] += $ranking->set_won;
                $stats['set_persi'] += $ranking->set_lost;
                $stats['games_vinti'] += $ranking->games_won;
                $stats['games_persi'] += $ranking->games_lost;
                $stats['points'] += $ranking->points;

                $match = Match::where('id', '=', $ranking->id_match)->first();
                if($match):
                    $round = Round::where('matchcode', '=', $match->matchcode)->first();
                    if( $round ):
                        $arr_tournaments[] = $round->group->division->id_tournament;
                    else:
                        $phase = Phase::where('matchcode', '=', $match->matchcode)->first();
                        if($phase):
                            $arr_tournaments[] = $phase->bracket->id_tournament;
                        endif;
                    endif;
                endif;
            endforeach;


            $arr_tournaments = array_unique($arr_tournaments);
            $stats['eventi'] = count($arr_tournaments);
            */

            $rankings =  app('App\Http\Controllers\Admin\RankingController')->classifica(User::find($id_player)->gender);
            $posizione = 0;
            $punti = 0;

            foreach($rankings['classifica'] as $r):
                if($r->id_player == $id_player):
                    $posizione = $rankings['posizioni'][$r->points];
                    $punti = $r->points;
                endif;
            endforeach;

            $matches = MatchPlayer::where('id_player', '=', $id_player)->get();

            $editions = [];
            $editions_ids = [];
            $matchPlayers = [];
            $scores = [];

            foreach($matches as $match):
                if( $match->match ):

                    $players_team1 = MatchPlayer::where('id_match', '=', $match->match->id)
                                                ->where('id_team', '=', $match->match->id_team1)
                                                ->get();

                    foreach($players_team1 as $matchPlayer){
                        $matchPlayers[$match->match->id]['team1'][] = $matchPlayer->player;
                    }



                    $players_team2 = MatchPlayer::where('id_match', '=', $match->match->id)
                                                ->where('id_team', '=', $match->match->id_team2)
                                                ->get();

                    foreach($players_team2 as $matchPlayer){
                        $matchPlayers[$match->match->id]['team2'][] = $matchPlayer->player;
                    }

                    foreach($match->match->scores as $score){

                        if($score->id_team == $match->match->id_team1):
                            $scores[$match->match->id][$score->set]['team1'] = $score->points;
                        else:
                            $scores[$match->match->id][$score->set]['team2'] = $score->points;
                        endif;

                    }

                    if($match->match->matchcodes->ref_type == 'round'):
                        $round = Round::where('id', '=', $match->match->matchcodes->id_ref)->first();
                        if($round):
                            if( !in_array($round->group->division->tournament->edition->id, $editions_ids) ):
                                $editions[] = $round->group->division->tournament->edition;
                                $editions_ids[] = $round->group->division->tournament->edition->id;
                            endif;
                        endif;
                    elseif($match->match->matchcodes->ref_type == 'phase'):
                        $phase = Phase::where('id', '=', $match->match->matchcodes->id_ref)->first();
                        if($phase):
                            if( !in_array($phase->bracket->tournament->edition->id, $editions_ids) ):
                                $editions[] = $phase->bracket->tournament->edition;
                                $editions_ids[] = $phase->bracket->tournament->edition->id;
                            endif;
                        endif;
                    endif;
                endif;
            endforeach;

            foreach($editions as $k => $edition){
                $editions[$k]['srcImgFeaturedBig'] = asset('storage/' . $edition->logo );
                $editions[$k]['srcImgFeaturedBig2'] = asset('storage/'. $edition->logo );
            }

            $metaLabels = [];

            foreach($meta_values as $k => $meta){
                $metaLabels[$k] = trans('labels.'.$k);
            }

            $metaLabels['eta'] = 'Età';
            $metaTypes['eta'] = 'string';

            $avatar_club = '';
            if($player->clubs){
                foreach($player->clubs->user->metas as $meta){

                    if($meta->meta == 'avatar'){
                        $avatar_club = asset('storage/'.$meta->meta_value);
                    }

                }
            }
            

            return view('single-profilo')
                        ->with('player', $player)
                        ->with('metas', $arr_metas)
                        ->with('meta_values', $meta_values)
                        ->with('sliders', $sliders)
                        ->with('clubs', implode(',', $clubs))
                        //->with('stats', $stats)
                        ->with('matches', $matches)
                        ->with('scores', $scores)
                        ->with('match_players', $matchPlayers)
                        ->with('meta_labels', $metaLabels)
                        ->with('meta_types', $metaTypes)
                        ->with('meta_values', $metaValues)
                        ->with('editions', $editions)
                        ->with('posizione', $posizione)
                        ->with('punti', $punti)
                        ->with('avatar_club', $avatar_club)
                        ->with('meta_private', $metaPrivate)
                        ;
        elseif( $user->id_role == 3 ):

            $club = Club::where('id_user', '=', $user->id)->first();

            $metas = [];

            foreach($user->metas as $meta):
                if( $meta->meta == 'avatar'):
                    $metas['avatar'] = $meta->meta_value;
                endif;
            endforeach;

            return view('single-profilo-circolo')
                        ->with('user', $user)
                        ->with('club', $club)
                        ->with('metas', $metas)
                        ;


        elseif( $user->id_role == 4 ):

            $partner = Partner::where('id_user', '=', $user->id)->first();

            $metas = [];

            foreach($user->metas as $meta):
                if( $meta->meta == 'avatar'):
                    $metas['avatar'] = $meta->meta_value;
                endif;
            endforeach;

            return view('single-profilo-partner')
                        ->with('user', $user)
                        ->with('partner', $partner)
                        ->with('metas', $metas)
                        ;

        endif;
    }
}
