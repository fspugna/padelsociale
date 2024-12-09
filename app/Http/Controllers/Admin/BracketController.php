<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateBracketRequest;
use App\Http\Requests\UpdateBracketRequest;
use App\Repositories\BracketRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

use App\Models\Bracket;
use App\Models\Match;
use App\Models\MacroMatch;
use App\Models\Score;
use App\Models\Phase;
use App\Models\PhaseTeam;
use App\Models\Club;
use App\Models\User;
use App\Models\Team;
use App\Models\TeamPlayer;

use App\Models\Edition;
use App\Models\EditionZone;
use App\Models\ZoneClub;

use App\Models\Tournament;
use App\Models\Matchcode;
use App\Models\Subscription;
use App\Models\MacroSubscription;
use Illuminate\Support\Facades\Log;

class BracketController extends AppBaseController
{
    /** @var  BracketRepository */
    private $bracketRepository;

    public function __construct(BracketRepository $bracketRepo)
    {
        $this->bracketRepository = $bracketRepo;
    }

    /**
     * Display a listing of the Bracket.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $brackets = $this->bracketRepository->all();

        return view('brackets.index')
            ->with('brackets', $brackets)
                ;
    }

    /**
     * Show the form for creating a new Bracket.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $input = $request->all();

        $bracket = new Bracket();
        $bracket->id_tournament = $input['id_tournament'];
        $bracket->id_zone = $input['id_zone'];
        $bracket->id_category_type = $input['id_category_type'];
        $bracket->id_category = $input['id_category'];
        $bracket->generated = 0;
        $bracket->note = '';
        $bracket->save();

        $res = array('status' => 'ok');
        return response()->json( $res );
    }

    /**
     * Store a newly created Bracket in storage.
     *
     * @param CreateBracketRequest $request
     *
     * @return Response
     */
    public function store(CreateBracketRequest $request)
    {
        $input = $request->all();

        $bracket = $this->bracketRepository->create($input);

        Flash::success('Bracket saved successfully.');

        return redirect(route('brackets.index'));
    }

    /**
     * Display the specified Bracket.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id_bracket)
    {
        $bracket = Bracket::find($id_bracket);

        $phase = Phase::where('id_bracket', '=', $bracket->id)->orderBy('id', 'ASC')->first();

        $start = Match::where('matchcode', '=', $phase->matchcode)->count();

        $fase = 1;

        $my_team = null;
        $allScores = [];
        $my_matches = [];
        $phases_descriptions = [];

        for($i=$start; $i>=1; $i=$i/2 ):

            $arr_phases[$fase] = $i;

            $iPhase = Phase::where('id_bracket', '=', $bracket->id)
                            ->where('name', '=', $fase)
                            ->first();

            $matches = Match::where('matchcode', '=', $iPhase->matchcode)->get();

            $phases_descriptions[$fase] = $iPhase->description;

            $arr_matches[$fase] = $matches;

            foreach($matches as $match):
                if($match->team1):
                    foreach($match->team1->players as $player):
                        if($player->player->id == Auth::id() || User::where('id', '=', Auth::id())->first()->id_role == 1):
                            $my_matches[$match->id] = [];
                            $my_matches[$match->id] = $match;

                            $my_team = $match->team1;
                        endif;
                    endforeach;
                endif;

                if($match->team2):
                    foreach($match->team2->players as $player):
                        if($player->player->id == Auth::id() || User::where('id', '=', Auth::id())->first()->id_role == 1):
                            $my_matches[$match->id] = [];
                            $my_matches[$match->id] = $match;

                            $my_team = $match->team2;
                        endif;
                    endforeach;
                endif;

                if(!empty($match->scores)):
                    foreach($match->scores as $score):
                        if($score->id_team == $match->id_team1):
                            $allScores[$match->id]['scores'][$score->set][$match->id_team1] = $score->points;
                        elseif($score->id_team == $match->id_team2):
                            $allScores[$match->id]['scores'][$score->set][$match->id_team2] = $score->points;
                        endif;
                    endforeach;
                endif;

            endforeach;

            $fase++;

        endfor;



        $positions = [];
        $can_change = true;

        $phase = Phase::where('id_bracket', '=', $id_bracket)
                            ->where('name', '=', '1')
                            ->first();

        $matches = Match::where('matchcode', '=', $phase->matchcode)->orderBy('id', 'ASC')->get();

        foreach($matches as $k => $match):
            $positions[$match->id.'L'] = ($k+1) . " - Sinistro";
            $positions[$match->id.'R'] = ($k+1) . " - Destro";

            if(Score::where('id_match', '=', $match->id)->first()):
                $can_change = false;
            endif;
        endforeach;

        $zonesEdition = EditionZone::where('id_edition', '=', $bracket->tournament->edition->id)->get();
        foreach($zonesEdition as $zoneEdition){
            $clubsList = ZoneClub::where('id_zone', '=', $zoneEdition->id_zone)->get();
            foreach($clubsList as $clubList){
                $clubs[] = Club::where('id', '=', $clubList->id_club)->first()->pluck('name', 'id');
            }
        }


        $cur_tournaemnt = Tournament::where('id', '=', $bracket->id_tournament)->first();
        foreach($cur_tournaemnt->edition->tournaments as $tournament){
            if($tournament->id_tournament_type == 1 && $tournament->id < $cur_tournaemnt->id){
                $fase_a_gironi = $tournament->id;
            }
        }

        $subscriptions = Subscription::where('id_tournament', '=', $fase_a_gironi)
                                            ->where('id_zone', '=', $bracket->id_zone)
                                            ->where('id_category_type', '=', $bracket->id_category_type)
                                            ->where('id_category', '=', $bracket->id_category)
                                            ->get();

        return view('admin.brackets.show')
                    ->with('bracket', $bracket )
                    ->with('arr_phases', $arr_phases )
                    ->with('arr_matches', $arr_matches )
                    ->with('my_matches', $my_matches )
                    ->with('allScores', $allScores )
                    ->with('my_team', $my_team)
                    ->with('positions', $positions)
                    ->with('can_change', $can_change)
                    ->with('clubs', $clubs)
                    ->with('phases_descriptions', $phases_descriptions)
                    ->with('subscriptions', $subscriptions)
                    ;

    }

    public function showMacro($id_bracket, Request $request)
    {

        $details =  $this->macroBracketDetails($id_bracket);

        $sel_macro_match = null;
        if ($request->has('macro_match')) {
            $sel_macro_match = $request->input('macro_match');
        }

        $details['sel_macro_match'] = $sel_macro_match;


        return view('admin.brackets.show_macro', $details);
    }

    public function macroBracketDetails($id_bracket) {
        $bracket = Bracket::find($id_bracket);

        $phase = Phase::where('id_bracket', '=', $bracket->id)->orderBy('id', 'ASC')->first();

        $start = MacroMatch::where('matchcode', '=', $phase->matchcode)->count();

        $fase = 1;

        $my_team = null;
        $allScores = [];
        $my_matches = [];
        $phases_descriptions = [];

        $arr_phases = [];
        $phases = [];
        $allMacroScores = [];

        for($i=$start; $i>=1; $i=$i/2 ):

            $arr_phases[$fase] = $i;

            $iPhase = Phase::where('id_bracket', '=', $bracket->id)
                            ->where('name', '=', $fase)
                            ->first();

            $phases[$fase] = $iPhase;

            $macroMatches = MacroMatch::where('matchcode', '=', $iPhase->matchcode)->get();

            $phases_descriptions[$fase] = $iPhase->description;

            $arr_matches[$fase] = $macroMatches;

            foreach($macroMatches as $macroMatch):

                $allMacroScores[$macroMatch->id] = [];
                $allMacroScores[$macroMatch->id][$macroMatch->id_team1] = null;
                $allMacroScores[$macroMatch->id][$macroMatch->id_team2] = null;

                foreach($macroMatch->macro_scores as $macroScore):
                    if($macroScore->id_team == $macroMatch->id_team1):
                        $allMacroScores[$macroMatch->id][$macroMatch->id_team1] += $macroScore->points;
                    elseif($macroScore->id_team == $macroMatch->id_team2):
                        $allMacroScores[$macroMatch->id][$macroMatch->id_team2] += $macroScore->points;
                    endif;
                endforeach;


                if(isset($macroMatch->subMatches)):
                    foreach($macroMatch->subMatches as $match):
                        if($match->team1):
                            foreach($match->team1->players as $player):
                                if($player->player->id == Auth::id() || User::where('id', '=', Auth::id())->first()->id_role == 1):
                                    $my_matches[$match->id] = [];
                                    $my_matches[$match->id] = $match;

                                    $my_team = $match->team1;
                                endif;
                            endforeach;
                        endif;

                        if($match->team2):
                            foreach($match->team2->players as $player):
                                if($player->player->id == Auth::id() || User::where('id', '=', Auth::id())->first()->id_role == 1):
                                    $my_matches[$match->id] = [];
                                    $my_matches[$match->id] = $match;

                                    $my_team = $match->team2;
                                endif;
                            endforeach;
                        endif;

                        if(!empty($match->scores)):
                            foreach($match->scores as $score):
                                if($score->id_team == $match->id_team1):
                                    $allScores[$match->id]['scores'][$score->set][$match->id_team1] = $score->points;
                                elseif($score->id_team == $match->id_team2):
                                    $allScores[$match->id]['scores'][$score->set][$match->id_team2] = $score->points;
                                endif;
                            endforeach;
                        endif;
                    endforeach;
                endif;
            endforeach;

            $fase++;

        endfor;


        $positions = [];
        $can_change = true;

        $phase = Phase::where('id_bracket', '=', $id_bracket)
                            ->where('name', '=', '1')
                            ->first();

        $macroMatches = MacroMatch::where('matchcode', '=', $phase->matchcode)->orderBy('id', 'ASC')->get();

        foreach($macroMatches as $k => $macroMatch):
            $positions[$macroMatch->id.'L'] = ($k+1) . " - Sinistro";
            $positions[$macroMatch->id.'R'] = ($k+1) . " - Destro";

            if(Score::where('id_match', '=', $macroMatch->id)->first()):
                $can_change = false;
            endif;
        endforeach;

        $zonesEdition = EditionZone::where('id_edition', '=', $bracket->tournament->edition->id)->get();
        foreach($zonesEdition as $zoneEdition){
            $clubsList = ZoneClub::where('id_zone', '=', $zoneEdition->id_zone)->get();
            foreach($clubsList as $clubList){
                $clubs[] = Club::where('id', '=', $clubList->id_club)->first()->pluck('name', 'id');
            }
        }


        $cur_tournaemnt = Tournament::where('id', '=', $bracket->id_tournament)->first();
        foreach($cur_tournaemnt->edition->tournaments as $tournament){
            if($tournament->id_tournament_type == 1 && $tournament->id < $cur_tournaemnt->id){
                $fase_a_gironi = $tournament->id;
            }
        }

        $subscriptions = MacroSubscription::where('id_tournament', '=', $fase_a_gironi)
                                            ->where('id_zone', '=', $bracket->id_zone)
                                            ->where('id_category_type', '=', $bracket->id_category_type)
                                            ->where('id_category', '=', $bracket->id_category)
                                            ->get();


        return array('bracket' => $bracket,
                    'arr_phases' => $arr_phases,
                    'phases' => $phases,
                    'my_matches' => $my_matches,
                    'allScores' => $allScores,
                    'arr_matches' => $arr_matches,
                    'my_team' => $my_team,
                    'positions' => $positions,
                    'can_change' => $can_change,
                    'clubs' => $clubs,
                    'phases_descriptions' => $phases_descriptions,
                    'subscriptions' => $subscriptions,
                    'allMacroScores' => $allMacroScores);

    }


    /**
     * Show the form for editing the specified Bracket.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $input = $request->all();

        $bracket = $this->bracketRepository->find($id);

        if (empty($bracket)) {
            Flash::error('Bracket not found');

            return redirect(route('brackets.index'));
        }


        if( in_array( $bracket->tournament->edition->edition_type , [0, 1] ) ){

            $res = $this->editBracket($bracket, $input, $id);

        }elseif( $bracket->tournament->edition->edition_type == 2 ){

            $res = $this->editMacroBracket($bracket, $input, $id);

        }

        return redirect( $res );
    }



    public function editBracket($bracket, $input, $id){

        if( $bracket->edit_mode == 0):
            $bracket->flag_online = 0;
            $bracket->edit_mode = 1;
        else:
            foreach($input as $k => $description):
                /* Aggiorno le descrizioni */
                if( strpos($k, 'description-') !== false ):
                    $phase_name = substr($k, strlen('description-'));
                    foreach($bracket->phases as $phase):
                        if($phase_name == $phase->name):
                            $newPhase = Phase::where('id_bracket', '=', $bracket->id)
                                                ->where('name', '=', $phase_name)
                                                ->first();
                            //dd($k, $i, $phase_name, $phase->name, $newPhase);
                            $newPhase->description = $description;
                            $newPhase->save();
                        endif;
                    endforeach;
                /* Salvo le squadre */
                elseif( strpos($k, 'team1_') !== false ):

                    $id_match = explode('_', $k)[1];
                    $id_team1 = $input[$k];

                    $match = Match::find($id_match);
                    $match->id_team1 = $id_team1;
                    $match->save();

                elseif( strpos($k, 'team2_') !== false ):

                    $id_match = explode('_', $k)[1];
                    $id_team2 = $input[$k];

                    $match = Match::find($id_match);
                    $match->id_team2 = $id_team2;
                    $match->save();

                endif;
            endforeach;


            $check = [];
            $id_matches = [];

            foreach($input as $k => $i):
                if( strpos($k, 'position_') !== false ):
                    $id_match = explode("position_", $k )[1];
                    $check[$id_match] = null;
                    $id_matches[] = substr($id_match, 0, strlen($id_match)-1);
                endif;
            endforeach;

            $id_matches = array_unique($id_matches);

            /* Salvo la disposizione precedente degli incontri */
            $res_old_matches = Match::whereIn('id', $id_matches)->get()->toArray();
            $old_matches = [];
            foreach($res_old_matches as $m):
                $old_matches[$m['id']] = $m;
            endforeach;

            /** Salvo i cambi */
            foreach($input as $k => $i):
                if( strpos($k, 'position_') !== false ):
                    $check[$i] = explode("position_", $k )[1];
                endif;
            endforeach;

            if( !empty($check) ):
            if( min($check) == null){
                return back()->withInput()->withErrors('Non hai compilato correttamente gli incontri');
            }else{
                foreach($check as $old => $new):
                    if($old !== $new):

                        /** La squadra in $k si sposta in $val */
                        $old_id_match = substr($old, 0, strlen($old)-1);
                        $new_id_match = substr($new, 0, strlen($new)-1);
                        $old_side = substr($old, -1);
                        $new_side = substr($new, -1);

                        $match = Match::where('id', '=', $new_id_match)->first();
                        if($new_side == 'L'):
                            if($old_side == 'L'):
                                $match->id_team1 = $old_matches[$old_id_match]['id_team1'];
                            elseif($old_side == 'R'):
                                $match->id_team1 = $old_matches[$old_id_match]['id_team2'];
                            endif;
                        elseif($new_side == 'R'):
                            if($old_side == 'L'):
                                $match->id_team2 = $old_matches[$old_id_match]['id_team1'];
                            elseif($old_side == 'R'):
                                $match->id_team2 = $old_matches[$old_id_match]['id_team2'];
                            endif;
                        endif;

                        $match->save();

                    endif;
                endforeach;
            }
            endif;

            $bracket->edit_mode = 0;
        endif;

        if( isset($input['bracket_note']) ):
            $bracket->note = $input['bracket_note'];
        endif;


        $bracket->save();

        return route('admin.show_bracket', ['id' => $id]);
    }


    public function editMacroBracket($bracket, $input, $id){

        if( $bracket->edit_mode == 0):
            $bracket->flag_online = 0;
            $bracket->edit_mode = 1;
        else:
            foreach($input as $index => $description):
                Log::info("Edit macro bracket description: " . $description);
                Log::info("Edit macro bracket index: " . $index);
                /* Aggiorno le descrizioni */
                if( strpos($index, 'description-') !== false ):
                    $phase_name = substr($index, strlen('description-'));
                    foreach($bracket->phases as $phase):
                        if($phase_name == $phase->name):
                            $newPhase = Phase::where('id_bracket', '=', $bracket->id)
                                                ->where('name', '=', $phase_name)
                                                ->first();
                            //dd($k, $i, $phase_name, $phase->name, $newPhase);
                            $newPhase->description = $description;
                            $newPhase->save();
                        endif;
                    endforeach;
                /* Salvo le squadre */
                elseif( strpos($index, 'macro_team1_') !== false ):

                    $id_macro_match = explode('_', $index)[2];
                    $id_macro_team1 = $input[$index];

                    $macroMatch = MacroMatch::find($id_macro_match);
                    $macroMatch->id_team1 = $id_macro_team1;
                    $macroMatch->save();

                elseif( strpos($index, 'macro_team2_') !== false ):

                    $id_macro_match = explode('_', $index)[2];
                    $id_macro_team2 = $input[$index];

                    $macroMatch = MacroMatch::find($id_macro_match);
                    $macroMatch->id_team2 = $id_macro_team2;
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

                    TeamPlayer::where('id_team', '=', $team1->id)->delete();

                    if( isset( $input['submatch_'.$match_id.'_team1_1'] ) ):
                        $id_player1 = $input['submatch_'.$match_id.'_team1_1'];
                        if( !empty($id_player1) ):
                            $teamPlayer = new TeamPlayer;
                            $teamPlayer->id_team = $team1->id;
                            $teamPlayer->id_player = $id_player1;
                            $teamPlayer->starter = 1;
                            $teamPlayer->save();
                        endif;
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

                    TeamPlayer::where('id_team', '=', $team2->id)->delete();

                    if( isset($input['submatch_'.$match_id.'_team2_1']) ):
                        $id_player1 = $input['submatch_'.$match_id.'_team2_1'];
                        if( !empty($id_player1) ):
                            $teamPlayer = new TeamPlayer;
                            $teamPlayer->id_team = $team2->id;
                            $teamPlayer->id_player = $id_player1;
                            $teamPlayer->starter = 1;
                            $teamPlayer->save();
                        endif;
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

                    $match = Match::where('id', '=', $match_id)->first();
                    $match->id_team2 = $team2->id;
                    $match->save();

                endif;
            endforeach;


            $check = [];
            $id_macro_matches = [];

            foreach($input as $k => $i):
                if( strpos($k, 'position_') !== false ):
                    $id_macro_match = explode("position_", $k )[1];
                    $check[$id_macro_match] = null;
                    $id_macro_matches[] = substr($id_macro_match, 0, strlen($id_macro_match)-1);
                endif;
            endforeach;

            $id_macro_matches = array_unique($id_macro_matches);

            /* Salvo la disposizione precedente degli incontri */
            $res_old_macro_matches = MacroMatch::whereIn('id', $id_macro_matches)->get()->toArray();
            $old_macro_matches = [];
            foreach($res_old_macro_matches as $m):
                $old_macro_matches[$m['id']] = $m;
            endforeach;

            /** Salvo i cambi */
            foreach($input as $k => $i):
                if( strpos($k, 'position_') !== false ):
                    $check[$i] = explode("position_", $k )[1];
                endif;
            endforeach;

            if( !empty($check) ):
            if( min($check) == null){
                return back()->withInput()->withErrors('Non hai compilato correttamente gli incontri');
            }else{
                foreach($check as $old => $new):
                    if($old !== $new):

                        /** La squadra in $k si sposta in $val */
                        $old_id_macro_match = substr($old, 0, strlen($old)-1);
                        $new_id_macro_match = substr($new, 0, strlen($new)-1);
                        $old_side = substr($old, -1);
                        $new_side = substr($new, -1);

                        $macroMatch = MacroMatch::where('id', '=', $new_id_macro_match)->first();
                        if($new_side == 'L'):
                            if($old_side == 'L'):
                                $macroMatch->id_macro_team1 = $old_macro_matches[$old_id_macro_match]['id_macro_team1'];
                            elseif($old_side == 'R'):
                                $macroMatch->id_macro_team1 = $old_macro_matches[$old_id_macro_match]['id_macro_team2'];
                            endif;
                        elseif($new_side == 'R'):
                            if($old_side == 'L'):
                                $macroMatch->id_macro_team2 = $old_macro_matches[$old_id_macro_match]['id_macro_team1'];
                            elseif($old_side == 'R'):
                                $macroMatch->id_macro_team2 = $old_macro_matches[$old_id_macro_match]['id_macro_team2'];
                            endif;
                        endif;

                        $macroMatch->save();

                    endif;
                endforeach;
            }
            endif;

            $bracket->edit_mode = 0;
        endif;

        if( isset($input['bracket_note']) ):
            $bracket->note = $input['bracket_note'];
        endif;

        $bracket->save();
        return route('admin.show_macro_bracket', ['id_bracket' => $id]);
    }

    /**
     * Update the specified Bracket in storage.
     *
     * @param int $id
     * @param UpdateBracketRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBracketRequest $request)
    {
        $bracket = $this->bracketRepository->find($id);

        if (empty($bracket)) {
            Flash::error('Bracket not found');

            return redirect(route('brackets.index'));
        }

        $bracket = $this->bracketRepository->update($request->all(), $id);

        Flash::success('Bracket updated successfully.');

        return redirect(route('brackets.index'));
    }

    /**
     * Remove the specified Bracket from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $bracket = $this->bracketRepository->find($id);

        if (empty($bracket)) {
            return response()->json(array('status' => 'ERROR' , 'msg' => 'Tabellone inesistente'));
        }

        $this->bracketRepository->delete($id);
        return response()->json(array('status' => 'OK' , 'msg' => 'Tabellone eliminato'));
    }

    public function addTeam(Request $request){
        $input = $request->all();

        try{
            $phase = Phase::where('id_bracket', '=', $input['id_bracket'])->first();

            $team = new Team;
            $team->save();

            $team->name = 'Squadra'.$team->id;
            $team->save();

            foreach($input['players'] as $id_player):
                $teamPlayer = new TeamPlayer;
                $teamPlayer->id_team = $team->id;
                $teamPlayer->id_player = $id_player;
                $teamPlayer->starter = 1;
                $teamPlayer->save();
            endforeach;

            $phaseTeam = new PhaseTeam;
            $phaseTeam->id_phase = $phase->id;
            $phaseTeam->id_team = $team->id;
            $phaseTeam->save();

            return response()->json(array('status' => 'ok'));
        }catch(\Exception $e){
            return response()->json(array('status' => 'error'));
        }
    }


    public function removeTeam(Request $request){
        $input = $request->all();

        Team::where('id', '=', $input['id_team'])->delete();
        PhaseTeam::where('id_phase', '=', $input['id_phase'])
                    ->where('id_team', '=', $input['id_team'])
                    ->delete();

        return response()->json(array('status' => 'ok'));
    }

    public function online(Request $request){
        $input = $request->all();

        $bracket = Bracket::where('id', '=', $input['id_bracket'])->first();
        if( $input['flag_online'] == 'true')
            $bracket->flag_online = 1;
        else
            $bracket->flag_online = 0;
        $bracket->save();

        /*
        if($bracket->flag_online == 1):
            foreach( $bracket->phases as $phase ):
                if($phase->name == 1):
                    foreach($phase->teams as $phaseTeam):
                        $teams[] = $phaseTeam->id_team;

                        foreach($phaseTeam->team->players as $teamPlayer):

                            //$teamPlayer->player->notify(new JoinBracket($bracket));

                        endforeach;

                    endforeach;
                endif;
            endforeach;
        endif;
         *
         */

        return response()->json(array('status'=>'OK'));
    }


    public function prepare ($id_bracket){
        $bracket = Bracket::find($id_bracket);

        $cur_tournaemnt = Tournament::where('id', '=', $bracket->id_tournament)->first();
        foreach($cur_tournaemnt->edition->tournaments as $tournament){
            if($tournament->id_tournament_type == 1 && $tournament->id < $cur_tournaemnt->id){
                $fase_a_gironi = $tournament->id;
            }
        }

        $subscriptions = Subscription::where('id_tournament', '=', $fase_a_gironi)
                                            ->where('id_zone', '=', $bracket->id_zone)
                                            ->where('id_category_type', '=', $bracket->id_category_type)
                                            ->where('id_category', '=', $bracket->id_category)
                                            ->get();

        return view('admin.brackets.prepare')->with('bracket', $bracket)
                                             ->with('subscriptions', $subscriptions);
    }

    public function generate (Request $request){

        $input = $request->all();

        $bracket = Bracket::find($input['id_bracket']);

        Phase::where('id_bracket', '=', $bracket->id)->delete();

        $phase = new Phase;
        $phase->id_bracket = $bracket->id;
        $phase->name = 1;
        $phase->save();

        $matchcode = new Matchcode;
        $matchcode->id_ref = $phase->id;
        $matchcode->ref_type = 'phase';
        $matchcode->save();

        $phase->matchcode = $matchcode->id;
        $phase->save();

        //$phase = Phase::where('id_bracket', '=', $bracket->id)->where('name', '=', 1)->first();

        if( in_array( $bracket->tournament->edition->edition_type , [0, 1] ) ):

            app('App\Http\Controllers\Admin\PhaseController')->generate($phase->id, intval($input['start_phase']));
            return redirect(route('admin.show_bracket', ['id_bracket' => $bracket->id]));

        elseif( $bracket->tournament->edition->edition_type == 2 ):

            app('App\Http\Controllers\Admin\PhaseController')->generate_macro($phase->id, intval($input['start_phase']));
            return redirect(route('admin.show_macro_bracket', ['id_bracket' => $bracket->id]));

        endif;

    }


}
