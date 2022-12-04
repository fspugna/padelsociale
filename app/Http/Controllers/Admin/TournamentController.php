<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateTournamentRequest;
use App\Http\Requests\UpdateTournamentRequest;
use App\Repositories\TournamentRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

use Carbon\Carbon;


use App\Models\Category;
use App\Models\CategoryType;
use App\Models\Division;
use App\Models\Edition;
use App\Models\EditionCategory;
use App\Models\EditionCategoryType;
use App\Models\EditionZone;
use App\Models\Subscription;
use App\Models\Group;
use App\Models\GroupTeam;
use App\Models\Team;
use App\Models\TeamPlayer;
use App\Models\Tournament;
use App\Models\TournamentType;
use App\Models\Zone;
use App\Models\ZoneClub;
use App\Models\User;
use App\Models\Matchcode;
use App\Models\Bracket;
use App\Models\Phase;
use App\Models\PhaseTeam;
use App\Models\Classification;

use App\Models\MacroTeam;
use App\Models\MacroTeamPlayer;
use App\Models\MacroSubscription;
use App\Models\GroupMacroTeam;
use App\Models\PhaseMacroTeam;

use App\Notifications\NewSubscription;


class TournamentController extends AppBaseController
{
    /** @var  TournamentRepository */
    private $tournamentRepository;

    public function __construct(TournamentRepository $tournamentRepo)
    {
        $this->tournamentRepository = $tournamentRepo;
    }

    /**
     * Display a listing of the Tournament.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $tournaments = $this->tournamentRepository->all();

        return view('admin.tournaments.index')
            ->with('tournaments', $tournaments);
    }

    /**
     * Show the form for creating a new Tournament.
     *
     * @return Response
     */
    public function create()
    {
        $tournament_types = TournamentType::all()->pluck('tournament_type', 'id');


        return view('admin.tournaments.create')
            ->with('tournament_types', $tournament_types);

    }

    /**
     * Store a newly created Tournament in storage.
     *
     * @param CreateTournamentRequest $request
     *
     * @return Response
     */
    public function store(CreateTournamentRequest $request)
    {
        $input = $request->all();

        if($input['id_tournament_type'] == 1):
            unset($input['id_tuornament_ref']);
        endif;

        /*
        $input['date_start'] = Carbon::createFromFormat('d/m/Y', $input['date_start'], 'Europe/London')->format('Y-m-d');
        $input['date_end'] = Carbon::createFromFormat('d/m/Y', $input['date_end'], 'Europe/London')->format('Y-m-d');
        */

        /*
        if(!empty( $input['registration_deadline_date'] )):
            $input['registration_deadline_date'] = Carbon::createFromFormat('d/m/Y', $input['registration_deadline_date'], 'Europe/London')->format('Y-m-d');
        endif;
        */

        //unset($input['daterange']);

        $tournament = $this->tournamentRepository->create($input);

        Flash::success('Tournament saved successfully.');

        $tournament = Tournament::with('tournamentType')->find($tournament->id);
        $res = $tournament->toArray();

        $res['date_start'] = $tournament->date_start->format('d/m/Y');
        $res['date_end'] = $tournament->date_end->format('d/m/Y');

        if(!empty( $input['registration_deadline_date'] )):
            $res['registration_deadline_date'] = $tournament->registration_deadline_date->format('d/m/Y');
        endif;

        $res['tournament_type']['tournament_type'] = trans('labels.'.$res['tournament_type']['tournament_type']);

        //return redirect(route('admin.tournaments.index'));
        return response()->json( $res );
    }

    /**
     * Display the specified Tournament.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $tournament = $this->tournamentRepository->find($id);

        if (empty($tournament)) {
            Flash::error('Tournament not found');

            return redirect(route('admin.tournaments.index'));
        }

        return view('admin.tournaments.show')->with('tournament', $tournament);
    }

    /**
     * Show the form for editing the specified Tournament.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tournament = $this->tournamentRepository->find($id);
        $tournament_types = TournamentType::all()->pluck('tournament_type', 'id');

        if (empty($tournament)) {
            Flash::error('Tournament not found');

            return redirect(route('admin.tournaments.index'));
        }


        $res['tournament'] = $tournament->toArray();

        $res['tournament']['date_start'] = $tournament->date_start->format('Y-m-d');
        $res['tournament']['date_end'] = $tournament->date_end->format('Y-m-d');
        if(!empty($tournament->registration_deadline_date)):
            $res['tournament']['registration_deadline_date'] = $tournament->registration_deadline_date->format('Y-m-d');
        endif;

        $res['tournament_types'] = $tournament_types->toArray();

        /*
        return view('admin.tournaments.edit')
            ->with('tournament', $tournament)
            ->with('tournament_types', $tournament_types);
            */

        return response()->json( $res );
    }

    /**
     * Update the specified Tournament in storage.
     *
     * @param int $id
     * @param UpdateTournamentRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTournamentRequest $request)
    {
        $tournament = $this->tournamentRepository->find($id);

        $input = $request->all();

        /*
        $input['date_start'] = Carbon::createFromFormat('d/m/Y', $input['date_start'], 'Europe/London')->format('Y-m-d');
        $input['date_end'] = Carbon::createFromFormat('d/m/Y', $input['date_end'], 'Europe/London')->format('Y-m-d');
        $input['registration_deadline_date'] = Carbon::createFromFormat('d/m/Y', $input['registration_deadline_date'], 'Europe/London')->format('Y-m-d');

        unset($input['daterange']);
        */

        if (empty($tournament)) {
            Flash::error('Tournament not found');

            return redirect(route('admin.tournaments.index'));
        }

        $tournament = $this->tournamentRepository->update($input, $id);

        Flash::success('Tournament updated successfully.');

        //return redirect(route('admin.editions.edit', ['edition' => $input['id_edition']]));
        $tournament = Tournament::with('tournamentType')->find($tournament->id);
        $res = $tournament->toArray();

        $res['date_start'] = $tournament->date_start->format('d/m/Y');
        $res['date_end'] = $tournament->date_end->format('d/m/Y');
        if(!empty($tournament->registration_deadline_date)):
            $res['registration_deadline_date'] = $tournament->registration_deadline_date->format('d/m/Y');
        endif;
        return response()->json( $res );
    }

    /**
     * Remove the specified Tournament from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $tournament = $this->tournamentRepository->find($id);

        if (empty($tournament)) {
            Flash::error('Tournament not found');

            return redirect(route('admin.tournaments.index'));
        }

        $this->tournamentRepository->delete($id);

        Flash::success('Tournament deleted successfully.');

        //return redirect(route('admin.tournaments.index'));

        return 'OK';
    }

    public function subscription($tournament_id){

        if(Auth::id()){
            $my_teams = TeamPlayer::where('id_player', '=', Auth::id())->get();

            foreach($my_teams as $team){

                $subscription = Subscription::where('id_tournament', '=', $tournament_id)
                                                ->where('id_team', '=', $team->id_team)
                                                ->first();

                if($subscription){
                    return view('admin.home')->withErrors('Risulti già iscritto a questo torneo!');
                }
            }
        }

        $tournament = Tournament::where('id', '=', $tournament_id)
                        ->with('divisions')
                        ->first();

        $zones = array();
        $zones[0] = 'Seleziona zona...';
        foreach($tournament->divisions as $division){
            $zona = Zone::where('id', '=', $division['id_zone'])->with('city')->first();
            $zones[$division['id_zone']] = $zona->city->name . ' - ' . $zona->name;
        }

        $arr_categoryTypes = EditionCategoryType::where('id_edition', '=', $tournament->id_edition)->get();
        $categoryTypes[0] = 'Seleziona tipologia...';
        foreach($arr_categoryTypes as $ct){
            $categoryTypes[$ct->categoryType->id] = $ct->categoryType->name;
        }

        /** Recupero la squadra già creata dell'utente se non è stata inscritta a nessun torneo */
        $team = Team::whereHas('players', function ($query) {
            $query->where('id_player', '=', Auth::id());
        })
        ->whereNotIn('id',function($query){
            $query->select('id_team')->from('subscriptions');
        })
        ->first()
        ;

        if(empty($team)){
            $team = new Team();
            $team->save();
            $team->name = 'Squadra' . $team->id;
            $team->save();

            $teamPlayer = new TeamPlayer();
            $teamPlayer->id_team = $team->id;
            $teamPlayer->id_player = Auth::id();
            $teamPlayer->starter = true;
            $teamPlayer->save();
        }

        /*
        $teamPlayers = TeamPlayer::with('player')
            ->where('id_team', '=', $team->id)
            ->get();
            */

        /*
            $players = User::where('id_role', '=', 2)
                        ->where('status', '=', 1)
                        ->get();
                        */
        $players = TeamPlayer::with('player')
                        ->where('id_team', '=', $team->id)
                        ->get();

        return view('admin.tournaments.subscription')
                    ->with('tournament', $tournament)
                    ->with('team', $team)
                    ->with('players', $players)
                    ->with('zones', $zones)
                    ->with('categoryTypes', $categoryTypes)
                    ;

        /*
        return view('page-iscrizione')
                    ->with('tournament', $tournament)
                    ->with('team', $team)
                    ->with('teamPlayers', $teamPlayers)
                    ->with('players', $players)
                    ->with('zones', $zones)
                    ->with('categoryTypes', $categoryTypes)
                    ;
                    */

    }


    public function subscribe_my_team(Request $request){
        $input = $request->all();


        if(isset($input['btn-subscribe'])){

            $my_teams = TeamPlayer::where('id_player', '=', Auth::id())->get();

            $errors = [];

            if($input['id_zone'] == 0){
                $errors[] = '<i class="fa fa-warning"></i> Selezionare la zona';
            }

            if($input['id_category_type'] == 0){
                $errors[] = '<i class="fa fa-warning"></i> Selezionare la tipologia';
            }

            $count = TeamPlayer::where('id_team', '=', $input['id_team'])->count();
            if($count < 2){
                $errors[] = '<i class="fa fa-warning"></i> La squadra deve avere almeno 2 giocatori';
            }

            if(!empty($errors)){
                return back()->withInput()->withErrors($errors);
            }

            $subscription = new Subscription;
            $subscription->id_tournament = $input['id_tournament'];
            $subscription->id_zone = $input['id_zone'];
            $subscription->id_category_type = $input['id_category_type'];
            $subscription->id_team = $input['id_team'];
            if( $subscription->save() ){

                /** Notify administrators about the new registration */
                // $users = User::where('id_role', '=', 1)->get();
                // foreach($users as $user):
                //     $user->notify(new NewSubscription($subscription));
                // endforeach;

                return view('admin.tournaments.subscription_confirmed');

            }
        }elseif(isset($input['btn-cancel-subscription'])){

            $team = Team::where('id', '=', $input['id_team'])->first();
            $team->delete();
            return view('admin.home');
        }

    }


    public function subscribe(Request $request){
        $input = $request->all();

        $edition = Tournament::find($input['id_tournament'])->edition;

        /* Se Torneo di Doppio */
        if( in_array( $edition->edition_type , [0, 1] ) ){

            $tot_players = ($edition->edition_type + 1);

            if( count($input['players']) >= $tot_players ){

                $team = new Team();
                $team->save();
                $team->name = 'Squadra'.$team->id;
                $team->save();

                foreach($input['players'] as $k => $id_player){

                    $teamPlayer = new TeamPlayer();
                    $teamPlayer->id_team = $team->id;
                    $teamPlayer->id_player = $id_player;
                    $teamPlayer->starter = ( $k < $tot_players ) ? 1 : 0;
                    $teamPlayer->save();

                }

                $subscription = new Subscription;
                $subscription->id_tournament = $input['id_tournament'];
                $subscription->id_zone = $input['id_zone'];
                $subscription->id_category_type = $input['id_category_type'];
                $subscription->id_team = $team->id;
                $subscription->save();

                $res = array('status' => 'ok');
                return response()->json($res);

            }elseif(isset($input['btn-cancel-subscription'])){

                $team = Team::where('id', '=', $input['id_team'])->first();
                $team->delete();
                return view('admin.home');
            }

        }elseif( $edition->edition_type == 2 ){
            /* Torneo a Squadre */

            if( count($input['players']) >= 5 ){

                $team = new MacroTeam();
                $team->id_club = $input['id_club'];
                $team->save();
                if( $input['team_name'] != '' ):
                    $team->name = $input['team_name'];
                else:
                    $team->name = 'Squadra'.$team->id;
                endif;
                $team->save();

                foreach($input['players'] as $k => $id_player){

                    $teamPlayer = new MacroTeamPlayer();
                    $teamPlayer->id_team = $team->id;
                    $teamPlayer->id_player = $id_player;
                    $teamPlayer->starter = ( $k < 5 ) ? 1 : 0;
                    $teamPlayer->save();

                }

                $subscription = new MacroSubscription;
                $subscription->id_tournament = $input['id_tournament'];
                $subscription->id_zone = $input['id_zone'];
                $subscription->id_category_type = $input['id_category_type'];
                $subscription->id_team = $team->id;
                $subscription->save();

                $res = array('status' => 'ok');
                return response()->json($res);

            }

        }

    }


    public function generate(Request $request){
        $input = $request->all();



        $tournament = Tournament::where('id', '=', $input['id_tournament'])
            ->with('edition')
            ->with('tournamentType')
            ->first()
            ;

        $edition_zones = EditionZone::where('id_edition', '=', $tournament['edition']['id'])->with('zone')->get()->toArray();
        $edition_categories = EditionCategory::where('id_edition', '=', $tournament['edition']['id'])->with('category')->get()->toArray();
        $edition_category_types = EditionCategoryType::where('id_edition', '=', $tournament['edition']['id'])->with('categoryType')->get()->toArray();



        if(count($edition_zones) === 0){
            return response()->json(array('error' => 'Selezionare almeno una zona'));
        }

        if(count($edition_categories) === 0){
            return response()->json(array('error' => 'Selezionare almeno una categoria'));
        }

        if(count($edition_category_types) === 0){
            return response()->json(array('error' => 'Selezionare almeno una tipologia'));
        }

        if( $tournament->tournamentType->tournament_type === 'step_1'){

            foreach($edition_zones as $zone){
                foreach($edition_categories as $category){
                    foreach($edition_category_types as $category_type){

                        /** Creo le divisioni */
                        $division = new Division();
                        $division->id_tournament = $tournament->id;
                        $division->id_zone = $zone['id_zone'];
                        $division->id_category = $category['id_category'];
                        $division->id_category_type = $category_type['id_category_type'];
                        $division->save();

                    }
                }
            }

        }elseif( $tournament->tournamentType->tournament_type === 'step_2'){

            $arr_teams = [];

            /*  Se esiste il torneo di riferimento */
            if( $tournament->id_tournament_ref > 0):

                 /** Creo i tabelloni prendendo i primi 2 classificati */

                $divisions = Division::where('id_tournament', '=', $tournament->id_tournament_ref)->get();


                // Creo i tabelloni in base alla divisione
                foreach($divisions as $division):

                    if($division->groups):
                        $bracket = Bracket::where('id_tournament', '=', $tournament->id)
                                        ->where('id_zone', '=', $division->id_zone)
                                        ->where('id_category', '=', $division->id_category)
                                        ->where('id_category_type', '=', $division->id_category_type)
                                        ->first();

                        if( !$bracket ):
                            $bracket = new Bracket;
                            $bracket->id_tournament = $tournament->id;
                            $bracket->id_zone = $division->id_zone;
                            $bracket->id_category = $division->id_category;
                            $bracket->id_category_type = $division->id_category_type;
                            $bracket->save();
                        endif;

                        foreach($division->groups as $group):
                            /** Recupero le prime 2 squadre di ogni gruppo e le unisco per categoria e tipologia */
                            $teams = Classification::where('id_group', '=', $group->id)->orderBy('points', 'DESC')->take(2)->get();

                            foreach($teams as $team):
                                $arr_teams [$division->id_zone][$division->id_category][$division->id_category_type][] = $team->id_team;
                            endforeach;

                        endforeach;

                    endif;
                endforeach;

            else:

                /** Altrimenti creo i tabelloni a partire dall'edizione */
                $tournament = Tournament::where('id', '=', $input['id_tournament'])
                    ->with('edition')
                    ->with('tournamentType')
                    ->first()
                    ;

                $edition_zones = EditionZone::where('id_edition', '=', $tournament['edition']['id'])->with('zone')->get()->toArray();
                $edition_categories = EditionCategory::where('id_edition', '=', $tournament['edition']['id'])->with('category')->get()->toArray();
                $edition_category_types = EditionCategoryType::where('id_edition', '=', $tournament['edition']['id'])->with('categoryType')->get()->toArray();

                if(count($edition_zones) === 0){
                    return response()->json(array('error' => 'Selezionare almeno una zona'));
                }

                if(count($edition_categories) === 0){
                    return response()->json(array('error' => 'Selezionare almeno una categoria'));
                }

                if(count($edition_category_types) === 0){
                    return response()->json(array('error' => 'Selezionare almeno una tipologia'));
                }


                foreach($edition_zones as $zone){
                    foreach($edition_categories as $category){
                        foreach($edition_category_types as $category_type){

                            $bracket = new Bracket;
                            $bracket->id_tournament = $tournament->id;
                            $bracket->id_zone =  $zone['id_zone'];
                            $bracket->id_category = $category['id_category'];
                            $bracket->id_category_type = $category_type['id_category_type'];
                            $bracket->save();

                        }
                    }
                }

            endif;

            /** Per ogni tabellone creo la prima fase con tutte le squadre */
            $brackets = Bracket::where('id_tournament', '=', $tournament->id)->get();

            foreach($brackets as  $bracket):

                $name = 1;

                $phase = new Phase;
                $phase->id_bracket = $bracket->id;
                $phase->name = $name;
                $phase->save();

                $matchcode = new Matchcode;
                $matchcode->id_ref = $phase->id;
                $matchcode->ref_type = 'phase';
                $matchcode->save();

                $phase->matchcode = $matchcode->id;
                $phase->save();

                $bracketTeams = null;

                foreach($arr_teams as $id_zone => $zones):

                    foreach($zones as $id_category => $category_types):

                        foreach($category_types as $id_category_type => $teams):

                            $bracketTeams = $arr_teams[$id_zone][$id_category][$id_category_type];

                            if(!empty($bracketTeams)):

                                /*$bracket = Bracket::where('id_tournament', '=', $tournament->id)
                                                    ->where('id_zone', '=', $id_zone)
                                                    ->where('id_category', '=', $id_category)
                                                    ->where('id_category_type', '=', $id_category_type)
                                                    ->first();
                                                    */

                                foreach($bracketTeams as $team_id):
                                    $phaseTeam = new PhaseTeam;
                                    $phaseTeam->id_phase = $phase->id;
                                    $phaseTeam->id_team = $team_id;
                                    $phaseTeam->save();
                                endforeach;

                            endif;

                            unset($arr_teams[$id_zone][$id_category][$id_category_type]);
                            unset($bracketTeams);

                        endforeach;

                    endforeach;

                endforeach;

                $name++;

            endforeach;

        }

        $tournament->generated = 1;
        $tournament->save();

        return response()->json(array('success' => 'Torneo generato con successo!'));
    }

    public function subscriptions($id_tournament){

        $divisions = Division::where('id_tournament', $id_tournament)
                            ->orderBy('id_zone', 'asc')
                            ->orderBy('id_category_type', 'asc')
                            ->orderBy('id_category', 'asc')
                            ->get();

        $tournament = Tournament::where('id', '=', $id_tournament)->first();

        $clubs = [];

        foreach($tournament->edition->zones as $editionZone){
            $zoneClubs = ZoneClub::where('id_zone', '=', $editionZone->zone->id)->get();
            foreach($zoneClubs as $zoneClub){
                $clubs [$zoneClub->club->id] = $zoneClub->club->name;
            }
        }

        $res_categories = EditionCategory::where('id_edition', '=', $tournament->id_edition)->get();
        $arr_categories = ['0' => 'Assegna categoria'];
        foreach($res_categories as $cat):
            $arr_categories[$cat->id_category] = $cat->category->name;
        endforeach;

        $no_cat = [];

        foreach($divisions as $division):

            if( in_array($tournament->edition->edition_type , [0, 1] ) ){

                $subscriptions_no_cat = Subscription::where('id_tournament', '=', $division->id_tournament)
                                                    ->where('id_zone', '=', $division->id_zone)
                                                    ->where('id_category_type', '=', $division->id_category_type)
                                                    ->where(function($query){
                                                        $query->where('id_category', '=', 0);
                                                        $query->orWhereNull('id_category');
                                                    })
                                                    ->get();

            }elseif( $tournament->edition->edition_type == 2 ){

                $subscriptions_no_cat = MacroSubscription::where('id_tournament', '=', $division->id_tournament)
                                                ->where('id_zone', '=', $division->id_zone)
                                                ->where('id_category_type', '=', $division->id_category_type)
                                                ->where(function($query){
                                                    $query->where('id_category', '=', 0);
                                                    $query->orWhereNull('id_category');
                                                })
                                                ->get();

                //dd($subscriptions_no_cat);
            }



            $no_cat[$division->id_zone][$division->id_category_type] = [];

            foreach($subscriptions_no_cat as $s):
                $no_cat[$division->id_zone][$division->id_category_type][] = $s;
            endforeach;
        endforeach;

        return view('admin.tournaments.subscriptions_new')
                ->with('divisions', $divisions)
                ->with('tournament', $tournament)
                ->with('arr_categories', $arr_categories)
                ->with('subscriptions_no_cat', $no_cat)
                ->with('clubs', $clubs)
                ;

        /*
        $res_subscriptions = Subscription::where('id_tournament', '=', $id_tournament)
                            ->with('tournament')
                            ->with('zone')
                            ->with('team')
                            ->with('categoryType')
                            ->get();

        $subscriptions = [];
        foreach($res_subscriptions as $subscription){

            $division = Division::where('id_tournament', $id_tournament)
                                    ->where('id_zone', '=', $subscription->id_zone)
                                    ->where('id_category_type', '=', $subscription->id_category_type)
                                    ->where('id_category', '=', $subscription->id_category)
                                    ->first();



            $subscriptions['zones'][$subscription->id_zone]['name'] = $subscription->zone->city->name . ' - ' . $subscription->zone->name;
            $subscriptions['zones'][$subscription->id_zone]['category_types'][$subscription->id_category_type]['name'] = $subscription->categoryType->name;
            $subscriptions['zones'][$subscription->id_zone]['category_types'][$subscription->id_category_type]['categories'][empty($subscription->id_category) ? 0 : $subscription->id_category]['name'] = empty($subscription->category['name']) ? 'Nessuna categoria' : $subscription->category->name;
            $subscriptions['zones'][$subscription->id_zone]['category_types'][$subscription->id_category_type]['categories'][empty($subscription->id_category) ? 0 : $subscription->id_category]['division'] = $division;
            $subscriptions['zones'][$subscription->id_zone]['category_types'][$subscription->id_category_type]['categories'][empty($subscription->id_category) ? 0 : $subscription->id_category]['values'][] = $subscription;
        }

        //dd($subscriptions);

        $tournament = Tournament::where('id', '=', $id_tournament)->first();
        $res_categories = EditionCategory::where('id_edition', '=', $tournament->id_edition)->get();
        $arr_categories = ['0' => 'Assegna categoria'];
        foreach($res_categories as $cat):
            $arr_categories[$cat->id_category] = $cat->category->name;
        endforeach;

        return view('admin.tournaments.subscriptions')
                    ->with('tournament', $tournament)
                    ->with('subscriptions', $subscriptions)
                    ->with('arr_categories', $arr_categories);

        */
    }

    public function assigncategories(Request $request){
        $input = $request->all();

        $tournament = Tournament::find($input['id_tournament']);

        foreach($input['subscription'] as $id_subscription => $id_category):

            if($tournament->edition->edition_type < 2):

                $subscription = Subscription::where('id', '=', $id_subscription)->first();

            elseif($tournament->edition->edition_type == 2):

                $subscription = MacroSubscription::where('id', '=', $id_subscription)->first();

            endif;

            $subscription->id_category = $id_category;
            $subscription->save();

        endforeach;


        return redirect(route('admin.subscriptions', ['id_tournament' => $input['id_tournament']]));
    }


    /** Current tournaments of a player */
    public function currents(){

        $player = User::where('id', '=', Auth::id())->first();
        $playerTeams = $player->teams;

        //dd($playerTeams->toArray(), Auth::id());

        $now = Carbon::now('Europe/Rome');

        $currents = array();


        $tournaments = Tournament::where('generated', '=', 1)
                                    ->orderBy('date_start', 'DESC')
                                    ->get();

        foreach($tournaments as $tournament):

            if($tournament->id_tournament_type == 1):

                $subscriptions = Subscription::where('id_tournament', '=', $tournament->id)->get();

                foreach($subscriptions as $subscription):
                    if($subscription->team):
                        foreach($playerTeams as $playerTeam):
                            if($playerTeam->id == $subscription->team->id):

                                $division = Division::where('id_tournament', $subscription->id_tournament)
                                    ->where('id_zone', '=', $subscription->id_zone)
                                    ->where('id_category_type', '=', $subscription->id_category_type)
                                    ->where('id_category', '=', $subscription->id_category)
                                    ->where('edit_mode', '=', '0')
                                    ->where('generated', '=', '1')
                                    ->orderBy('id', 'DESC')
                                    ->first();

                                if($division):
                                    $groups = Group::where('id_division', '=', $division->id)
                                                    ->where('flag_online', '=', 1)
                                                    ->get();

                                    foreach($groups as $group):
                                        foreach($group->teams as $groupTeam):
                                            if($groupTeam->id_team == $playerTeam->id):
                                                $currents[] = ['division' => $division , 'subscription' => $subscription, 'group' => $group];
                                            endif;
                                        endforeach;
                                    endforeach;

                                endif;

                            endif;
                        endforeach;
                    endif;
                endforeach;

            elseif($tournament->id_tournament_type == 2):

                $brackets = Bracket::where('id_tournament', '=', $tournament->id)
                                    ->where('flag_online', '=', 1)
                                    ->get();

                foreach($brackets as $bracket):
                    if($bracket->phases):
                        foreach($bracket->phases as $phase):
                            if($phase->name == 1):
                                if($phase->teams):
                                    foreach($phase->teams as $phaseTeam):
                                        foreach($playerTeams as $playerTeam):
                                            if($phaseTeam->id_team == $playerTeam->id):
                                                $currents[] = ['bracket' => $bracket];
                                            endif;
                                        endforeach;
                                    endforeach;
                                endif;
                            endif;
                        endforeach;
                    endif;
                endforeach;

            endif;

        endforeach;

        /*
        foreach($teams as $team):

            foreach($team->subscriptions as $subscription):
                //$subscription->tournament->date_start->lt($now) &&
                if( $subscription->tournament->date_end->gt($now) ){

                    $division = Division::where('id_tournament', $subscription->id_tournament)
                                    ->where('id_zone', '=', $subscription->id_zone)
                                    ->where('id_category_type', '=', $subscription->id_category_type)
                                    ->where('id_category', '=', $subscription->id_category)
                                    ->where('edit_mode', '=', '0')
                                    ->where('generated', '=', '1')
                                    ->orderBy('id', 'DESC')
                                    ->first();

                    if($division):
                        $groups = Group::where('id_division', '=', $division->id)
                                       ->where('flag_online', '=', 1)
                                       ->get();

                        foreach($groups as $group):
                            foreach($group->teams as $groupTeam):
                                if($groupTeam->id_team == $team->id):
                                    $currents[] = ['division' => $division , 'subscription' => $subscription, 'group' => $group];
                                endif;
                            endforeach;
                        endforeach;

                    endif;
                }
            endforeach;

            $phasesTeam = PhaseTeam::where('id_team', '=', $team->id)->get();
            foreach($phasesTeam as $phaseTeam):
                $phase = Phase::where('id', '=', $phaseTeam->id_phase)->first();
                $bracket = Bracket::where('id', '=', $phase->id_bracket)
                                        ->where('flag_online', '=', 1)
                                        ->orderBy('id', 'DESC')
                                        ->first();
                if($bracket):
                    $currents[] = ['bracket' => $bracket];
                endif;

            endforeach;

        endforeach;
        */

        return view('admin.tournaments.currents')
                    ->with('currents', $currents)
                    ;
    }


    public function myteam($id_tournament){
        $tournament = Tournament::where('id', '=', $id_tournament)->first();

        /** Se è la prima fase posso fare una sostituzione altrimenti no */
        $edition = $tournament->edition;
        $edition_tournaments = Tournament::where('id_edition', '=', $edition->id)->orderBy('id', 'ASC')->first();

        if( intval($id_tournament) === intval($edition_tournaments->id) ){
            $can_change = true;
        }else{
            $can_change = false;
        }

        $my_team = null;
        $arr_teams = [];

        if( $tournament->id_tournament_type == 1):
            foreach($tournament->divisions as $d):
                foreach($d->groups as $g):
                    foreach($g->teams as $t):
                        foreach($t->team->players as $p):

                            $arr_teams[$t->team->id]['team'] = $t->team;
                            $arr_teams[$t->team->id]['players'][] = $p;

                            if($p->player->id == Auth::id()):
                                $my_team = $t->team->id;
                            endif;
                        endforeach;
                    endforeach;
                endforeach;
            endforeach;
        else:
            foreach($tournament->brackets as $b):
                foreach($b->phases as $g):
                    foreach($g->teams as $t):
                        foreach($t->team->players as $p):

                            $arr_teams[$t->team->id]['team'] = $t->team;
                            $arr_teams[$t->team->id]['players'][] = $p;

                            if($p->player->id == Auth::id()):
                                $my_team = $t->team->id;
                            endif;
                        endforeach;
                    endforeach;
                endforeach;
            endforeach;
        endif;

        return view('admin.tournaments.myteam')
                ->with('myteam', $arr_teams[$my_team])
                ->with('can_change', $can_change)
                ;

    }



    public function brackets($id_tournament){
        $tournament = Tournament::find($id_tournament);
        $edition = $tournament->edition;

        $fase_a_gironi = Tournament::where('id_edition', $edition->id)
                                    ->where('id_tournament_type', 1)
                                    ->first();

        $tabellone = Tournament::where('id_edition', $edition->id)
                               ->where('id_tournament_type', 2)
                               ->first();

        $divisions = Division::where('id_tournament', '=', $fase_a_gironi->id)->get();

        $arr_brackets = [];

        foreach($divisions as $division):

            $bracket = Bracket::where('id_tournament', '=', $tabellone->id)
                              ->where('id_zone', $division->id_zone)
                              ->where('id_category', $division->id_category)
                              ->where('id_category_type', $division->id_category_type)
                              ->first();

            if(empty($bracket)):
                $bracket = new Bracket();
                $bracket->id_tournament = $tabellone->id;
                $bracket->id_zone = $division->id_zone;
                $bracket->id_category = $division->id_category;
                $bracket->id_category_type = $division->id_category_type;
                $bracket->generated = 0;
                $bracket->edit_mode = 0;
                $bracket->flag_online = 0;
                $bracket->save();
            endif;

            if( $bracket ):
                $arr_brackets['zones'][$bracket->id_zone]['name'] = $bracket->zone->city->name . ' - ' . $bracket->zone->name;
                $arr_brackets['zones'][$bracket->id_zone]['category_types'][$bracket->id_category_type]['name'] = $bracket->categoryType->name;
                $arr_brackets['zones'][$bracket->id_zone]['category_types'][$bracket->id_category_type]['categories'][empty($bracket->id_category) ? 0 : $bracket->id_category]['name'] = empty($bracket->category['name']) ? 'Nessuna categoria' : $bracket->category->name;
                $arr_brackets['zones'][$bracket->id_zone]['category_types'][$bracket->id_category_type]['categories'][empty($bracket->id_category) ? 0 : $bracket->id_category]['values'][] = $bracket;
            endif;
        endforeach;

        return view('admin.brackets.index')
                    ->with('arr_brackets', $arr_brackets)
                    ->with('edition', $edition)
                    ->with('tournament', $tabellone)
                    ;
    }
}
