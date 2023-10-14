<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateMatchRequest;
use App\Http\Requests\UpdateMatchRequest;
use App\Repositories\MatchRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Flash;
use Response;

use Carbon\Carbon;

use App\Models\Match;
use App\Models\MacroMatch;
use App\Models\MacroScore;
use App\Models\MacroClassification;
use App\Models\Score;
use App\Models\Ranking;
use App\Models\Classification;
use App\Models\MatchPlayer;

class MatchController extends AppBaseController
{
    /** @var  MatchRepository */
    private $matchRepository;

    public function __construct(MatchRepository $matchRepo)
    {
        $this->matchRepository = $matchRepo;
    }

    /**
     * Display a listing of the Match.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $matches = $this->matchRepository->all();

        return view('admin.matches.index')
            ->with('matches', $matches);
    }

    /**
     * Show the form for creating a new Match.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.matches.create');
    }

    /**
     * Store a newly created Match in storage.
     *
     * @param CreateMatchRequest $request
     *
     * @return Response
     */
    public function store(CreateMatchRequest $request)
    {
        $input = $request->all();

        $match = $this->matchRepository->create($input);

        Flash::success('Match saved successfully.');

        return redirect(route('matches.index'));
    }

    /**
     * Display the specified Match.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $match = $this->matchRepository->find($id);

        if (empty($match)) {
            Flash::error('Match not found');

            return redirect(route('matches.index'));
        }

        return view('admin.matches.show')->with('match', $match);
    }

    /**
     * Show the form for editing the specified Match.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $match = $this->matchRepository->find($id);

        if (empty($match)) {
            Flash::error('Match not found');

            return redirect(route('matches.index'));
        }

        return view('admin.matches.edit')->with('match', $match);
    }

    /**
     * Update the specified Match in storage.
     *
     * @param int $id
     * @param UpdateMatchRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMatchRequest $request)
    {
        $match = $this->matchRepository->find($id);

        if (empty($match)) {
            Flash::error('Match not found');

            return redirect(route('matches.index'));
        }

        $match = $this->matchRepository->update($request->all(), $id);

        Flash::success('Match updated successfully.');

        return redirect(route('matches.index'));
    }

    /**
     * Remove the specified Match from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $match = $this->matchRepository->find($id);

        if (empty($match)) {
            Flash::error('Match not found');

            return redirect(route('matches.index'));
        }

        $this->matchRepository->delete($id);

        Flash::success('Match deleted successfully.');

        return redirect(route('matches.index'));
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

        $match = Match::where('id', '=', $input['id_match'])->first();
        $match->id_club = $input['match_club'];
        $match->date = $match_date;
        $match->time = Carbon::createFromFormat('H:i', $input['match_hours'], 'Europe/London')->format('H:i');

        $match->save();

        $res = ['status' => 'ok'];
        return response()->json( $res );
    }

    public function delSchedule(Request $request){

        $input = $request->all();

        $match = Match::where('id', '=', $input['id_match'])->first();
        $match->id_club = null;
        $match->date = null;
        $match->time = null;
        $match->save();

        $res = ['status' => 'ok'];
        return response()->json( $res );
    }

    public function delete($id_match){
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
        //Ranking::where('id_match', '=', $id_match)->delete();
        Classification::where('id_match', '=', $id_match)->delete();
        MatchPlayer::where('id_match', '=', $id_match)->delete();

        $match->delete();

        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        return back()->withInput();
    }

    public function deleteAjax($id_match){

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
        //Ranking::where('id_match', '=', $id_match)->delete();
        Classification::where('id_match', '=', $id_match)->delete();
        MatchPlayer::where('id_match', '=', $id_match)->delete();

        $match->delete();

        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        return Response()->json(array('status'=>'ok'));
    }

    public function pitch(Request $request, $id_match){
        $input = $request->all();
        $match = Match::find($id_match);
        $match->pitch = $input['pitch'];
        $match->save();
        return Response()->json(array('status'=>'ok'));
    }
}


