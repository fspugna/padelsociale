<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateDivisionRequest;
use App\Http\Requests\UpdateDivisionRequest;
use App\Repositories\DivisionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

use App\Models\Division;
use App\Models\Group;
use App\Models\GroupTeam;
use App\Models\Match;
use App\Models\Matchcode;
use App\Models\Classification;
use App\Models\Round;
use App\Models\Subscription;
use App\Models\Score;

use App\Models\MacroSubscription;
use App\Models\GroupMacroTeam;
use App\Models\MacroTeam;
use App\Models\MacroTeamPlayer;
use App\Models\MacroClassification;

class DivisionController extends AppBaseController
{
    /** @var  DivisionRepository */
    private $divisionRepository;

    public function __construct(DivisionRepository $divisionRepo)
    {
        $this->divisionRepository = $divisionRepo;
    }

    /**
     * Display a listing of the Group.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index($id_division)
    {                                        
        $division = Division::where('id', '=', $id_division)->first();                   
        
        $groups = Group::where('id_division', '=', $division->id)->get();    

        $matches_played = false;
        
        $group_teams = [];
        
        if( in_array( $division->tournament->edition->edition_type , [0, 1] ) ):

            /** Verifico se è stata giocata almeno una partita */
            foreach( $groups as $group ):

                $groupTeams = GroupTeam::where('id_group', '=', $group->id)->get();
                foreach($groupTeams as $groupTeam){
                    $group_teams [] = $groupTeam->id_team;
                }

                foreach( $group->rounds as $round ):
                    $matches = Match::where('matchcode', '=', $round->matchcode)->get();
                    foreach($matches as $match):
                        if( count($match->scores) > 0 ):
                            $matches_played = true;
                            break;
                        endif;
                    endforeach;
                endforeach;
            endforeach;


            $teamsNotInGroups = Subscription::where('id_tournament', '=', $division->id_tournament)
                                        ->where('id_zone', '=', $division->id_zone)
                                        ->where('id_category_type', '=', $division->id_category_type)
                                        ->where('id_category', '=', $division->id_category)
                                        ->whereNotIn('id_team', $group_teams)
                                        ->get();

            //dd($teamsNotInGroups);
            
        elseif( $division->tournament->edition->edition_type == 2 ):
            
            /** Verifico se è stata giocata almeno una partita */
            
            foreach( $groups as $group ):

                $groupTeams = GroupMacroTeam::where('id_group', '=', $group->id)->get();
                foreach($groupTeams as $groupTeam){
                    $group_teams [] = $groupTeam->id_team;
                }
                                                
                /*
                foreach( $group->rounds as $round ):
                    $matches = Match::where('matchcode', '=', $round->matchcode)->get();
                    foreach($matches as $match):
                        if( count($match->scores) > 0 ):
                            $matches_played = true;
                            break;
                        endif;
                    endforeach;
                endforeach;
                 * 
                 */
            endforeach;
                           
            $teamsNotInGroups = MacroSubscription::where('id_tournament', '=', $division->id_tournament)
                                                ->where('id_zone', '=', $division->id_zone)
                                                ->where('id_category_type', '=', $division->id_category_type)
                                                ->where('id_category', '=', $division->id_category)
                                                ->whereNotIn('id_team', $group_teams)
                                                ->get();
            
        endif;

        return view('admin.divisions.index')
            ->with('groups', $groups)
            ->with('division', $division)
            ->with('matches_played', $matches_played)
            ->with('teamsNotInGroup', $teamsNotInGroups)
            ;
    }

    /**
     * Show the form for creating a new Division.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $input = $request->all();

        $division = new Division();
        $division->id_tournament = $input['id_tournament'];
        $division->id_zone = $input['id_zone'];
        $division->id_category_type = $input['id_category_type'];
        $division->id_category = $input['id_category'];
        $division->generated = 0;
        $division->save();

        $res = array('status' => 'ok');
        return response()->json( $res );
    }

    /**
     * Store a newly created Division in storage.
     *
     * @param CreateDivisionRequest $request
     *
     * @return Response
     */
    public function store(CreateDivisionRequest $request)
    {
        $input = $request->all();

        $division = $this->divisionRepository->create($input);

        Flash::success('Division saved successfully.');

        return redirect(route('divisions.index'));
    }

    /**
     * Display the specified Division.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $division = $this->divisionRepository->find($id);

        if (empty($division)) {
            Flash::error('Division not found');

            return redirect(route('divisions.index'));
        }

        return view('divisions.show')->with('division', $division);
    }

    /**
     * Show the form for editing the specified Division.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id, Request $request)
    {
        $input = $request->all();      
                
        
        $division = Division::where('id', '=', $input['id_division'])->first();
        
        if( in_array('btn_substitute', array_keys($input) ) ):            
            foreach($input as $k => $val):            
                if(strpos($k, "substitute_") !== FALSE ):
                    if( !empty($val) ):                                                
                        
                        $id_team_old = explode("_", $k)[1];
                        $id_group = explode("_", $val)[0];
                        $id_team_new = explode("_", $val)[1];                                               
                        
                        /*
                        dump($id_team_old);
                        dump($id_group);
                        dump($id_team_new);
                        */

                        /*
                        $groupTeam = GroupTeam::where('id_group', '=', $id_group)
                                                ->where('id_team', '=', $id_team_old)
                                                ->first();
                        
                        $groupTeam->id_team = $id_team_new;                        
                        $groupTeam->save();
                         * 
                         */
                        
                        DB::table('group_teams')
                                ->where('id_group', '=', $id_group)
                                ->where('id_team', '=', $id_team_old)
                                ->update(['id_team' => DB::raw($id_team_new)]);
                        
                        $rounds = Round::where('id_group', '=', $id_group)->get();
                        
                        foreach($rounds as $round):
                            //dump($round->matches);
                            foreach($round->matches as $match):                                
                                if( $match->id_team1 == $id_team_old ):

                                    /** Sostituisco la squadra solo per i match non ancora giocati */
                                    $scores = Score::where('id_match', $match->id)
                                                    ->where('id_team', $id_team_old)
                                                    ->get();
                                                                                   
                                    if( $scores->count() === 0 ):
                                        //dump("team1 > " . $match->id_team1);
                                        $match->id_team1 = $id_team_new;
                                        $match->save();                                    
                                    endif;
                                    //dd($match, $id_team_old, $id_team_new);
                                    
                                elseif( $match->id_team2 == $id_team_old ):

                                    /** Sostituisco la squadra solo per i match non ancora giocati */
                                    $scores = Score::where('id_match', $match->id)
                                                    ->where('id_team', $id_team_old)
                                                    ->get();

                                    if( $scores->count() === 0 ):
                                        //dump("team2 > " . $match->id_team2);
                                        $match->id_team2 = $id_team_new;
                                        $match->save();
                                        //dd($match, $id_team_old, $id_team_new);                                    
                                    endif;                                    

                                endif;                                
                            endforeach;
                        endforeach;                                                    
                        //dd("OK");
                    endif;
                endif;
            endforeach;
        
        elseif( in_array('btn_edit_groups', array_keys($input) ) ):

            $group_teams = [];
            
            foreach( $division->groups as $group ):
                $group->flag_online = 0;
                $group->save();
            endforeach;                        

            
            $division->edit_mode = !$division->edit_mode;
            $division->save();

        elseif( in_array('btn_save_groups', array_keys($input) ) ):
                        
            /** Cerco il numero originale di gironi e di numero di squadre per girone */
            $num_gironi  = count($division->groups->toArray());            
    
            /*
            foreach( $division->groups as $group):
                $num_squadre_girone[$group->id] = ( count($group->teams->toArray()) == 0) ? 1 : count($group->teams->toArray());
            endforeach;            
            
            if( empty($num_squadre_girone) )
                return back()->withInput()->withErrors('Il girone non può essere vuoto');
                */

            /* Verifico quante squadre per gironi si sta cercando di salvare */
            
            $arr_groups = [];
            
            foreach( $input as $k => $val ):                
                if( explode("_", $k)[0] == 'team' ):
                    $arr_groups[$val] [] = $k;
                endif;
            endforeach;
            
            /*
            foreach($arr_groups as $group => $input_group):                
                if( count($input_group) !== $num_squadre_girone ):                                                                                
                    return back()->withInput()->withErrors('Ogni girone deve avere ' . $num_squadre_girone . ' squadre. Il gruppo ' . Group::find($group)->name . ' ha ' . count($input_group) . ' squadre');
                endif;
            endforeach;
            */

            /* Elimino le squadre dei gruppi per reinserirle nel nuovo ordine */
            if( in_array( $division->tournament->edition->edition_type , [0, 1] ) ):
                
                foreach($division->groups as $group):
                    foreach($group->teams as $groupTeam):                    
                        $gt = GroupTeam::where('id_group', '=', $group->id)
                                        ->where('id_team', '=', $groupTeam->id_team)
                                        ->delete();         

                        /*
                        Classification::where('id_group', '=', $group->id)
                                        ->where('id_team', '=', $groupTeam->id_team)
                                        ->delete();
                                        */
                                                
                    endforeach;                

                    /*
                    foreach($group->rounds as $round):
                        Round::where('id', '=', $round->id)->delete();
                    endforeach;
                    */

                endforeach;            
                
                foreach($arr_groups as $id_group => $teams):

                    $group = Group::where('id', '=', $id_group)->first();

                    if( $group && $id_group ){
                        /*
                        foreach( $group->rounds as $round ):
                            $matches = Match::where('matchcode', '=', $round->matchcodes->id)->get();
                            //Matchcode::where('id', '=', $round->matchcode)->delete();                    
                        endforeach;                
                        */

                        foreach($teams as $team):
                            $groupTeam = new GroupTeam;
                            $groupTeam->id_group = $id_group;
                            $groupTeam->id_team = explode("_", $team)[1];
                            $groupTeam->save();                            
                        endforeach;
                        
                        //app('\App\Http\Controllers\Admin\GroupController')->make_calendar($group);
                    }

                endforeach;
                
            elseif( $division->tournament->edition->edition_type == 2 ):
                                                
                foreach($division->groups as $group):
                    
                    //MacroClassification::where('id_group', '=', $group->id)->delete();
                
                    foreach($group->macro_teams as $groupMacroTeam):                    
                        $gt = GroupMacroTeam::where('id_group', '=', $group->id)
                                            ->where('id_team', '=', $groupMacroTeam->id_team)
                                            ->delete();                                

                    endforeach; 
                                        
                    /*
                    foreach($group->rounds as $round):
                        Round::where('id', '=', $round->id)->delete();
                    endforeach;
                    */

                endforeach;            
                
                
                foreach($arr_groups as $id_group => $teams):

                    $group = Group::where('id', '=', $id_group)->first();

                    if( $group && $id_group ){
                        /*
                        foreach( $group->rounds as $round ):
                            $matches = Match::where('matchcode', '=', $round->matchcodes->id)->get();
                            Matchcode::where('id', '=', $round->matchcode)->delete();                    
                        endforeach;                
                        */

                        foreach($teams as $team):
                            $groupMacroTeam = new GroupMacroTeam;
                            $groupMacroTeam->id_group = $id_group;
                            $groupMacroTeam->id_team = explode("_", $team)[1];
                            $groupMacroTeam->save();                           
                        endforeach;

                        //app('\App\Http\Controllers\Admin\GroupController')->make_calendar($group);
                    }

                endforeach;
                
            endif;                                     
            
            $division->edit_mode = !$division->edit_mode;
            $division->save();

        elseif( in_array('btn_add_group', array_keys($input) ) ):

            $last_name = Group::where('id_division', '=', $input['id_division'])->max('name');
            
            if ( empty($last_name) ){
                $last_name = 'A';
            }else{
                
                $last_name++;
            }
            
            $group = new Group;
            $group->name = $last_name;
            $group->id_division = $input['id_division'];
            $group->save();        
                                
        endif;

        
        

        return back();
    }

    /**
     * Update the specified Division in storage.
     *
     * @param int $id
     * @param UpdateDivisionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDivisionRequest $request)
    {
        $division = $this->divisionRepository->find($id);

        if (empty($division)) {
            Flash::error('Division not found');

            return redirect(route('divisions.index'));
        }

        $division = $this->divisionRepository->update($request->all(), $id);

        Flash::success('Division updated successfully.');

        return redirect(route('divisions.index'));
    }

    /**
     * Remove the specified Division from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $division = $this->divisionRepository->find($id);

        if (empty($division)) {
            Flash::error('Division not found');

            return redirect(route('divisions.index'));
        }

        $this->divisionRepository->delete($id);

        Flash::success('Division deleted successfully.');

        return response()->json( array('status' => 'ok') );
    }
    
}
