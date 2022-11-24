<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateTeamPlayerRequest;
use App\Http\Requests\UpdateTeamPlayerRequest;
use App\Repositories\TeamPlayerRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

use App\Models\TeamPlayer;

class TeamPlayerController extends AppBaseController
{
    /** @var  TeamPlayerRepository */
    private $teamPlayerRepository;

    public function __construct(TeamPlayerRepository $teamPlayerRepo)
    {
        $this->teamPlayerRepository = $teamPlayerRepo;
    }

    /**
     * Display a listing of the TeamPlayer.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $teamPlayers = $this->teamPlayerRepository->all();

        return view('admin.team_players.index')
            ->with('teamPlayers', $teamPlayers);
    }

    /**
     * Show the form for creating a new TeamPlayer.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.team_players.create');
    }

    /**
     * Store a newly created TeamPlayer in storage.
     *
     * @param CreateTeamPlayerRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();                  
        
        $starters = TeamPlayer::where('id_team', '=', $input['id_team'])
                                ->where('starter', '=', 1)
                                ->count();
                                        
        $teamPlayer = new TeamPlayer;
        $teamPlayer->id_team = $input['id_team'];
        $teamPlayer->id_player = $input['id_player'];
        if($starters < 2){
            $teamPlayer->starter = 1;
        }else{
            $teamPlayer->starter = 0;
        }
        $teamPlayer->save();

        //$player = TeamPlayer::where('id', '=', $teamPlayer->id)->with('player')->first()->toArray();
        
        $res = ['status' => 'ok'];
        return response()->json($res);
    }

    /**
     * Display the specified TeamPlayer.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $teamPlayer = $this->teamPlayerRepository->find($id);

        if (empty($teamPlayer)) {
            Flash::error('Team Player not found');

            return redirect(route('teamPlayers.index'));
        }

        return view('admin.team_players.show')->with('teamPlayer', $teamPlayer);
    }

    /**
     * Show the form for editing the specified TeamPlayer.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        
        $teamPlayer = $this->teamPlayerRepository->find($id);

        if (empty($teamPlayer)) {
            Flash::error('Team Player not found');

            return redirect(route('teamPlayers.index'));
        }

        return view('admin.team_players.edit')->with('teamPlayer', $teamPlayer);
    }

    /**
     * Update the specified TeamPlayer in storage.
     *
     * @param int $id
     * @param UpdateTeamPlayerRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTeamPlayerRequest $request)
    {
        $teamPlayer = $this->teamPlayerRepository->find($id);

        if (empty($teamPlayer)) {
            Flash::error('Team Player not found');

            return redirect(route('teamPlayers.index'));
        }

        $teamPlayer = $this->teamPlayerRepository->update($request->all(), $id);

        Flash::success('Team Player updated successfully.');

        return redirect(route('teamPlayers.index'));
    }

    /**
     * Remove the specified TeamPlayer from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {

        $teamPlayer = $this->teamPlayerRepository->find($id);

        $res = ['status' => 'ok'];

        /*
        if ($teamPlayer->id_player == Auth::id() ) {
            $res = ['status' => 'error', 'msg' => 'Non puoi togliere te stesso dalla squadra!'];
            return response()->json($res);            
        }
        */
        
        if( TeamPlayer::where('id', '=', $id)->delete($id) ){

            //Flash::success('Team Player deleted successfully.');
            //return redirect(route('teamPlayers.index'));
            return response()->json($res);

        }else{

            return response()->json(['status' => 'error']);

        }
    }
}
