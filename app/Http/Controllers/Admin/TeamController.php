<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;

use App\Http\Requests\CreateTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Repositories\TeamRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

use App\Models\Team;
use App\Models\TeamPlayer;
use App\Models\Subscription;
use App\Models\MacroTeam;
use App\Models\MacroSubscription;

class TeamController extends AppBaseController
{
    /** @var  TeamRepository */
    private $teamRepository;

    public function __construct(TeamRepository $teamRepo)
    {
        $this->teamRepository = $teamRepo;
    }

    /**
     * Display a listing of the Team.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $teams = $this->teamRepository->all();    
        
        return view('admin.teams.index')
            ->with('teams', $teams);
    }

    /**
     * Show the form for creating a new Team.
     *
     * @return Response
     */
    public function create()
    {        
        $team = new Team();
        $team->save();
        $team->name = 'Squadra' . $team->id;
        $team->save();

        $teamPlayer = new TeamPlayer();
        $teamPlayer->id_team = $team->id;
        $teamPlayer->id_player = Auth::id();
        $teamPlayer->starter = true;
        $teamPlayer->save();
        
        return redirect(route('admin.teams.edit', ['id' => $team->id]));        
    }

    /**
     * Store a newly created Team in storage.
     *
     * @param CreateTeamRequest $request
     *
     * @return Response
     */
    public function store(CreateTeamRequest $request)
    {
        $input = $request->all();

        $team = $this->teamRepository->create($input);

        Flash::success('Team saved successfully.');

        //return redirect(route('admin.teams.index'));
        return redirect(route('admin.subscription', ['id_tournament' => $input['id_tournament']]));
    }

    /**
     * Display the specified Team.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $team = $this->teamRepository->find($id);

        if (empty($team)) {
            Flash::error('Team not found');

            return redirect(route('admin.teams.index'));
        }

        return view('admin.teams.show')->with('team', $team);
    }

    /**
     * Show the form for editing the specified Team.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id_team)
    {        
        
        $team = Team::find($id_team);     
        
        $subscription = Subscription::where('id_team', $id_team)->first();
        if( !$subscription ):

            $macroTeam = MacroTeam::find($id_team);

            $macro_subscription = MacroSubscription::where('id_team', $id_team)->first();           
            
            $clubs = [];
            
            foreach($macro_subscription->zone->clubs as $zoneClub):                 
                $clubs[$zoneClub->club->id] = $zoneClub->club->name;
            endforeach;

            natsort($clubs);            

            return view('admin.macro_teams.edit')
                ->with('macroTeam', $macroTeam)
                ->with('macro_subscription', $macro_subscription)
                ->with('clubs', $clubs)
                ;

        endif;
          
        return view('admin.teams.edit')
                ->with('team', $team)
                ->with('subscription', $subscription)
                ;

    }

    /**
     * Update the specified Team in storage.
     *
     * @param int $id
     * @param UpdateTeamRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTeamRequest $request)
    {
        $input = $request->all();        
        
        $team = Team::find($id);        

        $starters = [];
        foreach($input as $k => $val):
            if( explode("_", $k)[0] == 'starter' ):
                $starters[] = explode("_", $k)[1];
            endif;
        endforeach;

        $subscription = Subscription::where('id_team', $team->id)->first();
        if($subscription->tournament->edition->edition_type == 0 && count($starters) != 1):
            return back()->withErrors(['msg' => 'Il titolare deve essere solamente un giocatore']);
        elseif($subscription->tournament->edition->edition_type == 1 && count($starters) != 2):
            return back()->withErrors(['msg' => 'Devi impostare 2 titolari']);
        elseif($subscription->tournament->edition->edition_type == 2 && count($starters) < 3):
            return back()->withErrors(['msg' => 'Devi impostare almeno 3 titolari']);
        endif;

        
        $old_starters = [];        
        foreach($team->players as $teamPlayer):
            if( $teamPlayer->starter == true ): 
                $old_starters[] = $teamPlayer->player->id;
            endif;
        endforeach;
                
        if($starters != $old_starters):
            $team->flag_change = true;
            $team->save();

            TeamPlayer::where('id_team', $team->id)->update(['starter' => 0]);

            foreach($starters as $starter):                            
                $team_player = TeamPlayer::where('id_team', $team->id)
                                        ->where('id_player', $starter)
                                        ->first();
                $team_player->starter = 1;
                $team_player->save();                                    
            endforeach;
        endif;


        return redirect(route('admin.teams.edit', ['id_team' => $team->id]));
    }

    /**
     * Remove the specified Team from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $team = $this->teamRepository->find($id);
        //dd($request->getRequestUri());
        if (empty($team)) {
            Flash::error('Team not found');

            return redirect(route('admin.teams.index'));
        }

        $this->teamRepository->delete($id);

        Flash::success('Team deleted successfully.');

        return redirect(route('admin.teams.index'));
    }


    public function destroyMyTeam(Request $request, $id)
    {
        $team = $this->teamRepository->find($id);
        
        if (empty($team)) {
            Flash::error('Team not found');

            return redirect(route('admin.teams.myteams'));
        }

        $this->teamRepository->delete($id);

        Flash::success('Team deleted successfully.');

        return redirect(route('admin.teams.myteams'));
    }


    public function myTeams(){
        $teams = TeamPlayer::where('id_player', '=', Auth::id())->with('team')->get();
        return view('admin.teams.myteams')->with('teams', $teams);
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
