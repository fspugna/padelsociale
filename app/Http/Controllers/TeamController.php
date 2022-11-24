<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\TeamPlayer;
use App\Models\Team;
use App\Models\GroupTeam;

class TeamController extends Controller
{
    public function show($id_team){
        $is_my_team = false;
        
        $can_change = false;
        
        $scores = [];
        
        if( Auth::id() ){
            $teams = TeamPlayer::where('id_player', '=', Auth::id())->get();
            foreach($teams as $team){
                if( $team->id_team == $id_team ){
                    $is_my_team = true;
                    
                    if($team->team->flag_change == 0){                               
                        foreach(GroupTeam::where('id_team', '=', $id_team)->first()->group->rounds as $round){
                            foreach($round->matches as $match){
                                if($match->id_team1 == $id_team || $match->id_team2 == $id_team){
                                    $scores[] = $match->scores->count();
                                    if($match->scores->count() == 0){
                                        /* Se ho partite ancora da giocare (Incontri senza risultato) */
                                        $can_change = true;                                        
                                    }
                                }
                            }
                        }                        
                    }
                    break;
                }
            }
        }
        
        $my_team = [];
        $team = Team::where('id', '=', $id_team)->first();
        $players = TeamPlayer::where('id_team', '=', $id_team)->orderBy('starter', 'desc')->get();        
        
        $my_team['team'] = $team;
        $my_team['players'] = $players;                
                
        return view('squadra')->with('my_team', $my_team)
                              ->with('is_my_team', $is_my_team)
                              ->with('id_team', $id_team)
                              ->with('can_change', $can_change)
                              ;
    }
    
    public function changePlayer(Request $request){
        $input = $request->all();
        $post_players = [];
        foreach($input as $k => $val):
            if( substr($k, 0, 6) == 'player'):
                $post_players[] = substr(substr($k, 7), 0, strlen(substr($k, 7))-8 );
            endif;
        endforeach;
        
        if(count($post_players) != 2 ):
            return back()->withInput()->withErrors(['I titolari devono essere 2']);            
        else:
            $team = Team::where('id', '=', $input['id_team'])->first();
            $old_starters = [];
            foreach($team->players as $player):                
                if($player->starter):                    
                    $old_starters[] = $player->player->id;                    
                endif;
            endforeach;

            sort($old_starters);
            sort($post_players);            

            if($old_starters == $post_players):
                return back()->withInput()->withErrors(['Non hai effettuato nessuna sostituzione']);            
            endif;

            foreach($team->players as $player):                
                if($player->starter):                    
                    $player->starter = false;
                    $player->save();    
                endif;
            endforeach;            

            foreach($team->players as $player):                                
                if(in_array( (string)$player->player->id, $post_players)):
                    $player->starter = true;
                    $player->save();                    
                endif;
            endforeach;

            $team->flag_change = true; 
            $team->save();
            
            return back();
        endif;
    }
    
}
