<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Group;
use App\Models\Classification;
use App\Models\Round;
use App\Models\Match;
use App\Models\Score;


class GroupController extends Controller
{
    public function show($id_group){

        $group = Group::where('id', '=' ,$id_group)->first();        
        $classification = Classification::where('id_group', '=', $id_group)->orderBy('points', 'DESC')->orderBy(DB::raw('games_won-games_lost'), 'DESC')->get();
        $rounds = Round::where('id_group', '=', $id_group)->orderBy('name', 'ASC')->get();

        $scores = [];

        foreach($rounds as $round){
            foreach($round->matches as $match){
                foreach($match->scores as $score){

                    if($score->id_team == $match->id_team1):
                        $scores[$round->id][$match->id][$score->set]['team1'] = $score->points;
                    else:
                        $scores[$round->id][$match->id][$score->set]['team2'] = $score->points;
                    endif;
                    
                }
            }            
        }               
        

        return view('single-girone')
                    ->with('classification', $classification)
                    ->with('rounds', $rounds)
                    ->with('scores', $scores)
                    ->with('group', $group)
                    ;
    }
    
}