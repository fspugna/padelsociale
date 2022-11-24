<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateRoundRequest;
use App\Http\Requests\UpdateRoundRequest;
use App\Repositories\RoundRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Flash;
use Response;

use App\Models\Round;
use App\Models\Club;
use App\Models\Group;
use App\Models\User;
use App\Models\Edition;
use App\Models\EditionZone;
use App\Models\ZoneClub;
use App\Models\Match;
use App\Models\Team;
use App\Models\TeamPlayer;
use App\Models\MacroMatch;
use App\Models\Score;
use App\Models\MatchPlayer;
use App\Models\MacroScore;
use App\Models\MacroClassification;
use App\Models\Ranking;
use App\Models\Classification;


class RoundController extends AppBaseController
{
    /** @var  RoundRepository */
    private $roundRepository;

    public function __construct(RoundRepository $roundRepo)
    {
        $this->roundRepository = $roundRepo;
    }

    /**
     * Display a listing of the Round.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index($id_group, Request $request)
    {

        $group = Group::where('id', '=', $id_group)->first();

        if( in_array( $group->division->tournament->edition->edition_type , [0, 1] ) ):
            $res = $this->indexDoppio($id_group);
            //dd($res);
            return view('admin.rounds.index')
                    ->with('rounds', $res['rounds'])
                    ->with('my_matches', $res['my_matches'])
                    ->with('allScores', $res['allScores'])
                    ->with('clubs', $res['clubs']);
        elseif( $group->division->tournament->edition->edition_type == 2 ):
            return $this->indexSquadre($id_group, $request);
        endif;


    }


    public function indexDoppio($id_group){
        $rounds = Round::where('id_group', '=', $id_group)->get();
        $my_matches = array();
        $allScores = array();

        foreach($rounds as $round):

            foreach($round->matchcodes->matches as $match):

                $my_matches[$match->id] = null;

                if( User::where('id', '=', Auth::id())->first()->id_role == 1 ):

                    $my_matches[$match->id] = $match;

                else:

                    foreach($match->team1->players as $player):

                        if($player->player->id == Auth::id()):

                            $my_matches[$match->id] = [];

                        endif;

                    endforeach;

                    foreach($match->team2->players as $player):

                        if($player->player->id == Auth::id()):

                            $my_matches[$match->id] = [];

                        endif;

                    endforeach;

                endif;

                if(!empty($match->scores)):
                    $scores = Score::where('id_match', '=', $match->id)
                        ->where('side', '=', 'team1')
                        ->get();
                    foreach($scores as $score):
                        $allScores[$match->id]['scores'][$score->set][$score->id_team] = $score->points;
                    endforeach;

                    $scores = Score::where('id_match', '=', $match->id)
                        ->where('side', '=', 'team2')
                        ->get();

                    foreach($scores as $score):
                        $allScores[$match->id]['scores'][$score->set][$score->id_team] = $score->points;
                    endforeach;

                    /*
                    foreach($match->scores as $score):
                        if($score->id_team == $match->id_team1):
                            $allScores[$match->id]['scores'][$score->set][$match->id_team1] = $score->points;
                        elseif($score->id_team == $match->id_team2):
                            $allScores[$match->id]['scores'][$score->set][$match->id_team2] = $score->points;
                        endif;
                    endforeach;
                    */
                endif;

            endforeach;
        endforeach;

        $clubs = null;
        $arrayClubs = [];
        $group = Group::where('id', '=', $id_group)->first();
        $zonesEdition = EditionZone::where('id_edition', '=', $group->division->tournament->edition->id)->get();
        foreach($zonesEdition as $zoneEdition){
            $clubsList = ZoneClub::where('id_zone', '=', $zoneEdition->id_zone)->get()->pluck('id_club')->toArray();
            foreach($clubsList as $k => $val){
                $arrayClubs[] = $val;
            }
        }

        $clubs = Club::whereIn('id', $arrayClubs)->get()->pluck('name', 'id');

        /*
        $clubs = Club::where('id_zone', '=', Group::where('id', '=', $id_group)->first()->division->id_zone)->get()->pluck('name', 'id');
        dd($clubs);
        */

        $res = [];
        $res['rounds'] = $rounds;
        $res['my_matches'] = $my_matches;
        $res['allScores'] = $allScores;
        $res['clubs'] = $clubs;

        return $res;
    }


    public function indexSquadre($id_group, Request $request){

        $sel_macro_match = null;
        if ($request->has('macro_match')) {
            $sel_macro_match = $request->input('macro_match');
        }

        $rounds = Round::where('id_group', '=', $id_group)->get();

        $group = Group::find($id_group);

        return view('admin.rounds.macro_index')
                    ->with('rounds', $rounds)
                    ->with('group', $group)
                    ->with('id_group', $id_group)
                    ;

    }


    /**
     * Show the form for creating a new Round.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.rounds.create');
    }

    /**
     * Store a newly created Round in storage.
     *
     * @param CreateRoundRequest $request
     *
     * @return Response
     */
    public function store(CreateRoundRequest $request)
    {
        $input = $request->all();

        $round = $this->roundRepository->create($input);

        Flash::success('Round saved successfully.');

        return redirect(route('rounds.index'));
    }

    /**
     * Display the specified Round.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $round = $this->roundRepository->find($id);

        if (empty($round)) {
            Flash::error('Round not found');

            return redirect(route('rounds.index'));
        }

        return view('admin.rounds.show')->with('round', $round);
    }

    /**
     * Show the form for editing the specified Round.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $round = $this->roundRepository->find($id);

        if (empty($round)) {
            Flash::error('Round not found');

            return redirect(route('rounds.index'));
        }

        return view('admin.rounds.edit')->with('round', $round);
    }

    /**
     * Update the specified Round in storage.
     *
     * @param int $id
     * @param UpdateRoundRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRoundRequest $request)
    {
        $round = $this->roundRepository->find($id);

        if (empty($round)) {
            Flash::error('Round not found');

            return redirect(route('rounds.index'));
        }

        $round = $this->roundRepository->update($request->all(), $id);

        Flash::success('Round updated successfully.');

        return redirect(route('rounds.index'));
    }

    /**
     * Remove the specified Round from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $round = $this->roundRepository->find($id);

        if (empty($round)) {
            Flash::error('Round not found');

            return redirect(route('rounds.index'));
        }

        $this->roundRepository->delete($id);

        Flash::success('Round deleted successfully.');

        return redirect(route('rounds.index'));
    }


    public function delete($id_round){

        $round = Round::where('id', '=', $id_round)->first();

        try{
            if( in_array( $round->group->division->tournament->edition->edition_type, [0, 1]) ):
                $match = Match::where('matchcode', '=', $round->matchcode)->first();

                Score::where('id_match', '=', $id_match)->delete();
                //Ranking::where('id_match', '=', $id_match)->delete();
                Classification::where('id_match', '=', $id_match)->delete();
                MatchPlayer::where('id_match', '=', $id_match)->delete();

                $match->delete();

            elseif( in_array( $round->group->division->tournament->edition->edition_type, [2]) ):

                $macroMatch = MacroMatch::where('matchcode', '=', $round->matchcode)->first();

                MacroClassification::where('id_match', '=', $macroMatch->id)->delete();

                foreach($macroMatch->subMatches as $match):

                    /* Rimuovo dal risultato totale della squadra il punteggio del match che sto cancellando */
                    MacroScore::where('id_submatch', '=', $match->id)->delete();
                    /***********************************************************/

                    $id_match = $match->id;
                    Score::where('id_match', '=', $id_match)->delete();
                    //Ranking::where('id_match', '=', $id_match)->delete();
                    Classification::where('id_match', '=', $id_match)->delete();
                    MatchPlayer::where('id_match', '=', $id_match)->delete();

                    $match->delete();

                endforeach;

                $macroMatch->delete();

            endif;
        }catch(\Exception $e){

        }

        Round::where('id', '=', $id_round)->delete();

        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        return back()->withInput();
    }


    public function addMacroMatch($id_round){
        $round = Round::where('id', '=', $id_round)->first();

        $macroMatch = new MacroMatch;
        $macroMatch->matchcode = $round->matchcode;
        $macroMatch->save();

        return back()->withInput();

    }

    public function saveMacroCalendar(Request $request){
        $input = $request->all();

        foreach($input as $index => $id_macro_team):

            $id_macro_match = null;
            if( strpos($index, 'id_team1') !== FALSE ):
                $id_macro_match = substr($index, strlen('id_team1_'));

                $macroMatch = MacroMatch::where('id', '=', $id_macro_match)->first();
                $macroMatch->id_team1 = $id_macro_team;
                $macroMatch->save();

            elseif( strpos($index, 'id_team2') !== FALSE ):
                $id_macro_match = substr($index, strlen('id_team2_'));

                $macroMatch = MacroMatch::where('id', '=', $id_macro_match)->first();
                $macroMatch->id_team2 = $id_macro_team;
                $macroMatch->save();

            elseif( strpos($index, 'submatch_') !== FALSE && strpos($index, 'team1_id') !== FALSE ):

                /* Squadra 1 e giocatori squadra 1 */
                if( empty($input[$index]) ):
                    $team1 = new Team;
                    $team1->save();
                    $team1->name = "Squadra " . $team1->id;
                    $team1->save();
                else:
                    $team1 = Team::where('id', '=', $input[$index])->first();
                endif;

                preg_match('/submatch_(.*?)_team1_id/', $index, $output_array);
                $match_id = $output_array[1];

                if( isset( $input['submatch_'.$match_id.'_team1_1'] ) ):
                    TeamPlayer::where('id_team', '=', $team1->id)->delete();

                    $id_player1 = $input['submatch_'.$match_id.'_team1_1'];
                    if( !empty($id_player1) ):
                        $teamPlayer = new TeamPlayer;
                        $teamPlayer->id_team = $team1->id;
                        $teamPlayer->id_player = $id_player1;
                        $teamPlayer->starter = 1;
                        $teamPlayer->save();
                    endif;

                    if( isset($input['submatch_'.$match_id.'_team1_2']) ):
                        $id_player2 = $input['submatch_'.$match_id.'_team1_2'];
                        if( !empty($id_player2) ):
                            $teamPlayer = new TeamPlayer;
                            $teamPlayer->id_team = $team1->id;
                            $teamPlayer->id_player = $id_player2;
                            $teamPlayer->starter = 1;
                            $teamPlayer->save();
                        endif;
                    endif;
                endif;

                $match = Match::where('id', '=', $match_id)->first();
                $match->id_team1 = $team1->id;
                $match->save();

            elseif( strpos($index, 'submatch_') !== FALSE && strpos($index, 'team2_id') !== FALSE ):
                /* Squadra 1 e giocatori squadra 2 */
                if( empty($input[$index]) ):
                    $team2 = new Team;
                    $team2->save();
                    $team2->name = "Squadra " . $team2->id;
                    $team2->save();
                else:
                    $team2 = Team::where('id', '=', $input[$index])->first();
                endif;

                preg_match('/submatch_(.*?)_team2_id/', $index, $output_array);
                $match_id = $output_array[1];

                if( isset($input['submatch_'.$match_id.'_team2_1']) ):

                    TeamPlayer::where('id_team', '=', $team2->id)->delete();

                    $id_player1 = $input['submatch_'.$match_id.'_team2_1'];
                    if( !empty($id_player1) ):
                        $teamPlayer = new TeamPlayer;
                        $teamPlayer->id_team = $team2->id;
                        $teamPlayer->id_player = $id_player1;
                        $teamPlayer->starter = 1;
                        $teamPlayer->save();
                    endif;

                    if( isset($input['submatch_'.$match_id.'_team2_2']) ):
                        $id_player2 = $input['submatch_'.$match_id.'_team2_2'];
                        if( !empty($id_player2) ):
                            $teamPlayer = new TeamPlayer;
                            $teamPlayer->id_team = $team2->id;
                            $teamPlayer->id_player = $id_player2;
                            $teamPlayer->starter = 1;
                            $teamPlayer->save();
                        endif;
                    endif;
                endif;

                $match = Match::where('id', '=', $match_id)->first();
                $match->id_team2 = $team2->id;
                $match->save();

            elseif( strpos($index, 'note_macro_match_') !== FALSE ):

                $macroMatch = MacroMatch::find(explode("_", $index)[count(explode("_", $index))-1]);
                $macroMatch->note = $input[$index];
                $macroMatch->save();

            elseif( strpos($index, 'jolly_team1_') !== FALSE ):

                $macroMatch = MacroMatch::find(explode("_", $index)[count(explode("_", $index))-1]);
                $macroMatch->jolly_team1 = $input[$index];
                $macroMatch->save();


            elseif( strpos($index, 'jolly_team2_') !== FALSE ):

                $macroMatch = MacroMatch::find(explode("_", $index)[count(explode("_", $index))-1]);
                $macroMatch->jolly_team2 = $input[$index];
                $macroMatch->save();

            endif;
            /*
            "submatch_6924_team1_id" => null
            "submatch_6924_team2_id" => null
             */
        endforeach;



        return back()->withInput();
    }

    public function matches($id_round, Request $request){

        $sel_macro_match = null;
        if ($request->has('macro_match')) {
            $sel_macro_match = $request->input('macro_match');
        }

        $round = Round::where('id', '=', $id_round)->first();

        $my_matches = array();
        $allScores = array();
        $allMacroScores = array();
        $match_players = [];

        foreach($round->matchcodes->macro_matches as $macro_match):

            $allMacroScores[$macro_match->id] = [];
            $allMacroScores[$macro_match->id][$macro_match->id_team1] = null;
            $allMacroScores[$macro_match->id][$macro_match->id_team2] = null;


            foreach($macro_match->macro_scores as $macroScore):
                if($macroScore->id_team == $macro_match->id_team1):
                    $allMacroScores[$macro_match->id][$macro_match->id_team1] += $macroScore->points;
                elseif($macroScore->id_team == $macro_match->id_team2):
                    $allMacroScores[$macro_match->id][$macro_match->id_team2] += $macroScore->points;
                endif;
            endforeach;


            foreach($macro_match->subMatches as $match):

                $my_matches[$match->id] = null;

                if(User::where('id', '=', Auth::id())->first()->id_role == 1):
                    $my_matches[$match->id] = $match;
                else:
                    if( isset($match->team1) ):
                        foreach($match->team1->players as $player):
                            if($player->player->id == Auth::id()):
                                $my_matches[$match->id] = [];
                            endif;
                        endforeach;
                    endif;

                    if( isset($match->team2) ):
                        foreach($match->team2->players as $player):
                            if($player->player->id == Auth::id()):
                                $my_matches[$match->id] = [];
                            endif;
                        endforeach;
                    endif;
                endif;

                $matchPlayers = MatchPlayer::where('id_match', '=', $match->id)->get();
                if( $matchPlayers->count() > 0 ):
                    foreach($matchPlayers as $matchPlayer):
                        $match_players[$matchPlayer->id_match][$matchPlayer->side][] = $matchPlayer;
                    endforeach;
                endif;


                if(count($match->scores)):

                    foreach($match->scores as $score):
                        if($score->id_team == $match->id_team1):
                            $allScores[$match->id]['scores'][$score->set][$match->id_team1] = $score->points;

                            /*
                            if( empty($allMacroScores[$macro_match->id][$macro_match->id_team1]) ){ $allMacroScores[$macro_match->id][$macro_match->id_team1] = 0; }
                            $allMacroScores[$macro_match->id][$macro_match->id_team1] = $allMacroScores[$macro_match->id][$macro_match->id_team1] + $score->points;
                            */
                        elseif($score->id_team == $match->id_team2):
                            $allScores[$match->id]['scores'][$score->set][$match->id_team2] = $score->points;
                            /*
                            if( empty($allMacroScores[$macro_match->id][$macro_match->id_team2]) ){ $allMacroScores[$macro_match->id][$macro_match->id_team2] = 0; }
                            $allMacroScores[$macro_match->id][$macro_match->id_team2] = $allMacroScores[$macro_match->id][$macro_match->id_team2] + $score->points;
                            */
                        endif;
                    endforeach;

                endif;

            endforeach;
        endforeach;


        $clubs = null;
        $arrayClubs = [];
        $group = Group::where('id', '=', $round->group->id)->first();
        $zonesEdition = EditionZone::where('id_edition', '=', $group->division->tournament->edition->id)->get();
        foreach($zonesEdition as $zoneEdition){
            $clubsList = ZoneClub::where('id_zone', '=', $zoneEdition->id_zone)->get()->pluck('id_club')->toArray();
            foreach($clubsList as $k => $val){
                $arrayClubs[] = $val;
            }
        }

        $clubs = Club::whereIn('id', $arrayClubs)->get()->pluck('name', 'id');

        return view('admin.rounds.matches')
                        ->with('round', $round)
                        ->with('my_matches', $my_matches)
                        ->with('allScores', $allScores)
                        ->with('allMacroScores', $allMacroScores)
                        ->with('clubs', $clubs)
                        ->with('group', $group)
                        ->with('sel_macro_match', $sel_macro_match)
                        ->with('match_players', $match_players)
                        ;
    }

}
