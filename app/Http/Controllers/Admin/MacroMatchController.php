<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\MacroMatch;
use App\Models\Match;
use App\Models\Score;
use App\Models\Ranking;
use App\Models\MatchPlayer;
use App\Models\Classification;
use App\Models\MacroScore;
use App\Models\Round;
use App\Models\Group;
use App\Models\MacroClassification;

use Carbon\Carbon;

class MacroMatchController extends Controller
{
    public function addSubMatch($id_macro_match, Request $request){

        $input = $request->all();

        $num_matches = 1;
        if(isset($input['num_matches'])):
            $num_matches = intval($input['num_matches']);
        endif;

        for($i=1; $i<=$num_matches; $i++):

            $macroMatch = MacroMatch::where('id', '=', $id_macro_match)->first();

            $subMatches = Match::where('id_macro_match', '=', $macroMatch->id)->get();

            $match_order = count($subMatches) + 1;

            $subMatch = new Match;
            $subMatch->matchcode = $macroMatch->matchcode;
            $subMatch->id_macro_match = $macroMatch->id;
            $subMatch->match_order = $match_order;
            $subMatch->save();

        endfor;

        return Response()->json(array('status'=>'ok'));
    }

    public function deleteMacroMatch($id_macro_match){
        $macroMatch = MacroMatch::find($id_macro_match);

        $matches = Match::where('id_macro_match', '=', $id_macro_match)->get();

        foreach($matches as $match):

            $id_match = $match->id;

            Score::where('id_match', '=', $id_match)->delete();
            //Ranking::where('id_match', '=', $id_match)->delete();
            MatchPlayer::where('id_match', '=', $id_match)->delete();
            Classification::where('id_match', '=', $id_match)->delete();
        endforeach;

        $macroMatch->delete();

        return response()->json(array("status"=>"OK"));
    }

    public function makeNull($id_macro_match){

        $macroMatch = MacroMatch::find($id_macro_match);

        MacroScore::where('id_match', '=', $macroMatch->id)->delete();

        $macroScore = new MacroScore;
        $macroScore->id_match = $macroMatch->id;
        $macroScore->id_submatch = 0;
        $macroScore->id_team = $macroMatch->id_team1;
        $macroScore->set = 1;
        $macroScore->points = 0;
        $macroScore->side = 'team1';
        $macroScore->save();

        $macroScore = new MacroScore;
        $macroScore->id_match = $macroMatch->id;
        $macroScore->id_submatch = 0;
        $macroScore->id_team = $macroMatch->id_team2;
        $macroScore->set = 1;
        $macroScore->points = 0;
        $macroScore->side = 'team2';
        $macroScore->save();

        $matches = Match::where('id_macro_match', '=', $id_macro_match)->get();

        foreach($matches as $match):
            $id_match = $match->id;

            Score::where('id_match', '=', $id_match)->delete();
            //Ranking::where('id_match', '=', $id_match)->delete();
            MatchPlayer::where('id_match', '=', $id_match)->delete();
            Classification::where('id_match', '=', $id_match)->delete();
        endforeach;

        if( $macroMatch->matchcodes->ref_type == 'round' ):
            $round = Round::where('id', '=', $macroMatch->matchcodes->id_ref)->first();
            $group = Group::where('id', '=', $round->id_group)->first();

            $classification = new MacroClassification;
            $classification->id_group = $group->id;
            $classification->id_match = $macroMatch->id;
            $classification->id_submatch = 0;
            $classification->id_team = $macroMatch->id_team1;
            $classification->points = 0;
            $classification->played = 1;
            $classification->won = 0;
            $classification->lost = 1;
            $classification->draws = 0;
            $classification->set_won = 0;
            $classification->set_lost = 0;
            $classification->games_won = 0;
            $classification->games_lost = 0;
            $classification->save();

            $classification = new MacroClassification;
            $classification->id_group = $group->id;
            $classification->id_match = $macroMatch->id;
            $classification->id_submatch = 0;
            $classification->id_team = $macroMatch->id_team2;
            $classification->points = 0;
            $classification->played = 1;
            $classification->won = 0;
            $classification->lost = 1;
            $classification->draws = 0;
            $classification->set_won = 0;
            $classification->set_lost = 0;
            $classification->games_won = 0;
            $classification->games_lost = 0;
            $classification->save();

        endif;


        return response()->json(array("status"=>"OK"));
    }


    public function schedule(Request $request){

        $input = $request->all();
        if( strpos($input['match_date'], "-") !== FALSE ){
            $match_date = Carbon::createFromFormat('Y-m-d H:i', $input['match_date'] . ' ' . $input['match_hours'], 'Europe/London');
        }else{
            $match_date = Carbon::createFromFormat('d/m/Y H:i', $input['match_date'] . ' ' . $input['match_hours'], 'Europe/London');
        }

        /*
        if( $match_date->isPast() ){
            $res = ['status' => 'error', 'msg' => trans('errors.match_date_before_today') . ' ' . $match_date];
            return response()->json( $res );
        }
        */

        $match = MacroMatch::where('id', '=', $input['id_macro_match'])->first();
        $match->id_club = $input['match_club'];
        $match->date = $match_date;
        $match->time = Carbon::createFromFormat('H:i', $input['match_hours'], 'Europe/London')->format('H:i');

        $match->save();

        $res = ['status' => 'ok'];
        return response()->json( $res );
    }

    public function delSchedule(Request $request){

        $input = $request->all();

        $match = MacroMatch::where('id', '=', $input['id_macro_match'])->first();
        $match->id_club = null;
        $match->date = null;
        $match->time = null;
        $match->save();

        $res = ['status' => 'ok'];
        return response()->json( $res );
    }

}
