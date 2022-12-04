<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Subscription;
use App\Models\Tournament;
use App\Models\Zone;
use App\Models\User;
use App\Models\Team;
use App\Models\TeamPlayer;

use App\Notifications\NewSubscription;

class SubscriptionController extends Controller
{

    public function list(Request $request){

        $input = $request->all();

        $id_tournament    = $input['id_tournament'];
        $id_zone          = $input['id_zone'];

        $subscriptions = Subscription::where('id_tournament', '=', $id_tournament)
                                     ->where('id_zone', '=', $id_zone)
                                     ->get();

        return response()->json($subscriptions);
    }

    public function subscription(Request $request){
        $input = $request->all();

        $tournament = Tournament::where('id', '=', $input['id_tournament'])->first();
        $tournament['srcImgFeaturedBig'] = asset('storage/'.$tournament->edition->logo);
        $tournament['srcImgFeaturedBigx2'] = asset('storage/'.$tournament->edition->logo);

        $categoryTypes = $tournament->edition->categoryTypes;

        $zone = Zone::where('id', '=', $input['id_zone'])->first();
        $zones = $tournament->edition->zones;
        $players = User::where('status', '=', 1)
                        ->where('id_role', '=', 2)
                        ->get();



        return view('page-iscrizione')
                    ->with('id_tournament', $input['id_tournament'])
                    ->with('id_zone', $input['id_zone'])
                    ->with('zone', $zone)
                    ->with('zones', $zones)
                    ->with('tournament', $tournament)
                    ->with('players', $players)
                    ->with('category_types', $categoryTypes)
                    ;

    }


    public function subscribe(Request $request){
        $input = $request->all();

        if(isset($input['select-category-type']) && isset($input['select-player-1']) && isset($input['select-player-2']) ):

            $team = new Team;
            $team->save();
            $team->name = 'Squadra' . $team->id;
            $team->save();

            $teamPlayer = new TeamPlayer;
            $teamPlayer->id_team = $team->id;
            $teamPlayer->id_player = $input['select-player-1'];
            $teamPlayer->starter = 1;
            $teamPlayer->save();

            $teamPlayer = new TeamPlayer;
            $teamPlayer->id_team = $team->id;
            $teamPlayer->id_player = $input['select-player-2'];
            $teamPlayer->starter = 1;
            $teamPlayer->save();

            if( isset($input['select-player-3']) ):
                $teamPlayer = new TeamPlayer;
                $teamPlayer->id_team = $team->id;
                $teamPlayer->id_player = $input['select-player-3'];
                $teamPlayer->starter = 0;
                $teamPlayer->save();
            endif;

            $subscription = new Subscription;
            $subscription->id_tournament = $input['id_tournament'];
            $subscription->id_zone = $input['id_zone'];
            $subscription->id_category_type = $input['select-category-type'];
            $subscription->id_team = $team->id;

            if( $subscription->save() ){

                /** Notify administrators about the new registration */
                // $users = User::where('id_role', '=', 1)->get();
                // foreach($users as $user):
                //     $user->notify(new NewSubscription($subscription));
                // endforeach;

                return view('page-conferma-iscrizione');
            }

        endif;

    }

}
