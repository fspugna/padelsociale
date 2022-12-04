<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Repositories\GroupRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

use App\Models\Subscription;
use App\Models\Division;
use App\Models\Group;
use App\Models\GroupTeam;
use App\Models\Round;
use App\Models\Match;
use App\Models\Matchcode;
use App\Models\Classification;
use App\Models\Team;
use App\Models\TeamPlayer;
use App\Models\Player;
use App\Models\Ranking;
use App\Models\Score;

use App\Models\MacroSubscription;
use App\Models\GroupMacroTeam;
use App\Models\MacroTeam;
use App\Models\MacroTeamPlayer;

use App\Models\Notifications\JoinGroup;

class GroupController extends AppBaseController
{
    /** @var  GroupRepository */
    private $groupRepository;

    public function __construct(GroupRepository $groupRepo)
    {
        $this->groupRepository = $groupRepo;
    }

    public function index()
    {

    }

    /**
     * Show the form for creating a new Group.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.groups.create');
    }

    /**
     * Store a newly created Group in storage.
     *
     * @param CreateGroupRequest $request
     *
     * @return Response
     */
    public function store(CreateGroupRequest $request)
    {
        $input = $request->all();

        $group = $this->groupRepository->create($input);

        Flash::success('Group saved successfully.');

        return redirect(route('admin.groups.index'));
    }

    /**
     * Display the specified Group.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $group = $this->groupRepository->find($id);

        if (empty($group)) {
            Flash::error('Group not found');

            return redirect(route('admin.groups.index'));
        }

        return view('admin.groups.show')->with('group', $group);
    }

    /**
     * Show the form for editing the specified Group.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $group = $this->groupRepository->find($id);

        if (empty($group)) {
            Flash::error('Group not found');

            return redirect(route('admin.groups.index'));
        }

        return view('admin.groups.edit')->with('group', $group);
    }

    /**
     * Update the specified Group in storage.
     *
     * @param int $id
     * @param UpdateGroupRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateGroupRequest $request)
    {
        $group = $this->groupRepository->find($id);

        if (empty($group)) {
            Flash::error('Group not found');

            return redirect(route('admin.groups.index'));
        }

        $group = $this->groupRepository->update($request->all(), $id);

        Flash::success('Group updated successfully.');

        return redirect(route('admin.groups.index'));
    }

    /**
     * Remove the specified Group from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $group = $this->groupRepository->find($id);

        if (empty($group)) {
            Flash::error('Group not found');

            return redirect(route('admin.groups.index'));
        }

        $this->groupRepository->delete($id);

        Flash::success('Group deleted successfully.');

        return redirect(route('admin.groups.index'));
    }


    public function prepare(Request $request){
        $input = $request->all();

        $division = Division::where('id', '=', $input['id_division'])->first();

        if( in_array( $division->tournament->edition->edition_type , [0,1] ) ){

            $subscriptions = Subscription::where('id_tournament', $division->id_tournament)
                                ->where('id_zone', $division->id_zone)
                                ->where('id_category_type', $division->id_category_type)
                                ->where('id_category', $division->id_category)
                                ->get()
                                ;

        }elseif( $division->tournament->edition->edition_type == 2 ){

            $subscriptions = MacroSubscription::where('id_tournament', $division->id_tournament)
                                ->where('id_zone', $division->id_zone)
                                ->where('id_category_type', $division->id_category_type)
                                ->where('id_category', $division->id_category)
                                ->get()
                                ;

        }

        return view('admin.groups.generate')
                //->with('options', $options)
                ->with('subscriptions', $subscriptions)
                ->with('division', $division)
                ;

    }

    /** Generazione automatica dei gironi in base al numero dei gironi richiesto */
    public function generate(Request $request){
        $input = $request->all();
        $num_gironi = $input['options'];
        $id_division = $input['id_division'];

        /** Recupero la divisione */
        $division = Division::where('id', '=', $id_division)->first();

        /** Creo i gironi */
        if( in_array( $division->tournament->edition->edition_type , [0,1] ) ):
            // Singolo e Doppio
            self::make_groups($num_gironi, $id_division);
        else:
            // Squadre
            self::make_macro_groups($num_gironi, $id_division);
        endif;


        return redirect(route('admin.subscriptions', ['id_tournament' => $division->id_tournament ]));
    }

    public static function make_groups($num_gironi, $id_division){

        // Cancello gli eventuali gironi precedenti della divisione
        Group::where('id_division', '=', $id_division)->delete();

        // Recupero la divisione
        $division = Division::where('id', '=', $id_division)->first();

        // Recupero gli iscritti della divisione
        $subscriptions_list = Subscription::where('id_tournament', '=', $division->id_tournament)
                                        ->where('id_zone', '=', $division->id_zone)
                                        ->where('id_category', '=', $division->id_category)
                                        ->where('id_category_type', '=', $division->id_category_type)
                                        ->get()
                                        ;

        // Creo un elendo dei soli ID squadra della divisione
        $subscriptions = [];
        foreach($subscriptions_list as $subscription):
            $subscriptions[] = $subscription->id_team;
        endforeach;

        // Calcolo il numero di squadre per girone in base al numero dei gironi richiesti
        $num_squadre_girone = count($subscriptions) / $num_gironi;
        if($num_squadre_girone <= 0) $num_squadre_girone = 1;

        $groupName = 'A';

        if( count($subscriptions) > 0){
            for($i=1;$i<=$num_gironi;$i++){

                // Creo il girone
                $group = new Group;
                $group->id_division = $division->id;
                $group->name = $groupName;
                $group->save();

                // Assegno in ordine casuale le squadre al girone fino a raggiungere il numero
                for($j=1; $j<=$num_squadre_girone; $j++){

                    // Estraggo a caso una squadra
                    $idTeamRand = rand(0, count($subscriptions)-1);

                    // Assegno la squadra al gruppo
                    $groupTeam = new GroupTeam;
                    $groupTeam->id_group = $group->id;
                    $groupTeam->id_team = $subscriptions[ $idTeamRand ];
                    $groupTeam->save();

                    // Rimuovo la squadra dall'elenco in modo da non estrarla di nuovo
                    unset($subscriptions[$idTeamRand]);
                    // Rigenero l'elenco in modo da non avere buchi negli id
                    $subscriptions = array_values($subscriptions);

                }

                // Genero il calendario del Girone
                self::make_calendar($group);

                $groupName++;
            }
        }

        $division->generated = 1;
        $division->save();

    }

    public static function make_calendar($group){

        // Recupero le eventuali giornate del girone se già presenti
        $rounds = Round::where('id_group', $group->id)->get();

        // Per ogni giornata elimino gli incontri
        foreach($rounds as $round):
            // Elimino gli incontri del girone
            $matches = Match::where('matchcode', $round->matchcode)->get();

            foreach($matches as $match):
                //Elimino eventuali risultati
                Score::where('id_match', $match->id)->delete();

                // Elimino l'incontro
                $match->delete();

                // Elino eventuali punti ranking associati alla partita
                //Ranking::where('id_match', $match->id)->delete();
            endforeach;

        endforeach;

        // Elimino la giornata
        Round::where('id_group', $group->id)->delete();

        // Elimino la classifica del girone
        Classification::where('id_group', $group->id)->delete();

        // Recupero tutte le squadre del girone
        $teams = GroupTeam::where('id_group', '=', $group->id)->get()->pluck('id_team')->toArray();

        // Calcolo il numero delle giornate in base al numero delle squadre nel girone
        $matches = [];
        if( count($teams) % 2 === 0 ):
            $tot_giornate = count($teams)-1;
            $dispari = false;
        else:
            $tot_giornate = count($teams);
            $dispari = true;
        endif;

        // Calcolo l'intervallo di date per la prima giornata in base alle date del torneo
        $date_start = $group->division->tournament->date_start;
        $date_end = $group->division->tournament->date_end;
        $diff = $date_start->diffInDays($date_end);
        // Calcolo i giorni disponibili per ogni singola giornata
        $days_per_round = ( $diff / $tot_giornate ) - 1;
        // Data inizio prima giornata
        $round_start = $date_start;
        // Data fine prima giornata
        $round_end = $date_start->copy()->addDays($days_per_round);

        $creati = [];

        // Creo le giornate
        for($giornata = 1; $giornata <= $tot_giornate; $giornata++):

            // Nuova giornata
            $round = new Round;
            // Associo la giornata al girone
            $round->id_group = $group->id;
            // Nome della giornata
            $round->name = $giornata;
            // Descrizione della giornata
            if($round_start->format('d/m/Y') !== $round_end->format('d/m/Y')):
                // Se intervallo date
                $round->description = 'Dal ' . $round_start->format('d/m/Y') . ' al ' . $round_end->format('d/m/Y');
            else:
                // Se singola giornata
                $round->description = $round_start->format('d/m/Y');
            endif;
            $round->save();

            // Data inizio giornata successiva
            $round_start = $round_end->copy()->addDays(1);
            // Data fine giornata successiva
            $round_end = $round_start->copy()->addDays($days_per_round);
            if($round_end > $date_end):
                $round_end = $date_end;
            endif;

            // Calcolo il numero degli incontri per ogni singola giornata in base alle squadre del girone
            $num_matches = intval( floor( count($teams) / 2 ) );

            // roundTeams contiene tutte le squadre del girone
            $roundTeams = $teams;

            // Tolgo la squadra che riposa
            // Una squadra riposa quando il numero delle squadre nel girone è dispari
            $riposa = null;
            if( $dispari ):
                $riposa = $roundTeams[$giornata-1];
                unset($roundTeams[$giornata-1]);
                $roundTeams = array_values($roundTeams);
            endif;

            $creati[$giornata]['riposa'] = $riposa;

            /** Genero un nuovo Matchdcode */
            $matchcode = new Matchcode;
            $matchcode->id_ref = $round->id;
            $matchcode->ref_type = 'round';
            $matchcode->save();

            $round->matchcode = $matchcode->id;
            $round->save();

            $count_exists = 0;
            $tentativi_squadre = [];

            $imatch = 1;

            while( $imatch <= $num_matches ):

                $tentativo_giornata = [];

                $appo_round_teams = $roundTeams;

                for($jmatch = 0; $jmatch<$num_matches; $jmatch++):

                    // Estraggo la prima squadra in ordine casuale
                    $rnd_id_1 = rand( 0 , count($appo_round_teams)-1 );
                    $id_team1 = $appo_round_teams[ $rnd_id_1 ];

                    // Rimuovo dall'elenco la squadra estratta per non estrarla 2 volte
                    unset($appo_round_teams[$rnd_id_1]);
                    $appo_round_teams = array_values($appo_round_teams);

                    // Estraggo la seconda squadra in ordine casuale
                    $rnd_id_2 = rand( 0 , count($appo_round_teams)-1 );
                    $id_team2 = $appo_round_teams[ $rnd_id_2 ];

                    // Rimuovo dall'elenco la squadra estratta per non estrarla 2 volte
                    unset($appo_round_teams[$rnd_id_2]);
                    $appo_round_teams = array_values($appo_round_teams);

                    $tentativo_giornata [] = array( $id_team1, $id_team2);

                endfor;

                // Verifico se nessuno degli incontri generati non è già presente
                // Es. Squadra 1 vs Squadra 2 è lo stesso incontro di Squadra 2 vs Squadra 1
                $posso_salvare = true;
                foreach($tentativo_giornata as $tg):

                    $match_exists = self::matchExists($group->id, $tg[0], $tg[1], $matches);
                    if($match_exists){
                        $posso_salvare = false;
                    }

                endforeach;

                // Se sono tutti incontri diversi
                if($posso_salvare):

                    foreach($tentativo_giornata as $tg):

                        // Creo l'incontro
                        $match = new Match();
                        $match->matchcode = $matchcode->id;
                        $match->id_team1 = $tg[0];
                        $match->id_team2 = $tg[1];
                        $match->save();

                        $match_teams = [$tg[0], $tg[1]];
                        sort( $match_teams );
                        $matches[$group->id][] = $match_teams;

                        $creati[$giornata]['squadre'][] = [$tg[0], $tg[1]];

                        if (($key = array_search($tg[0], $roundTeams)) !== false) {
                            unset($roundTeams[$key]);
                        }

                        if (($key = array_search($tg[1], $roundTeams)) !== false) {
                            unset($roundTeams[$key]);
                        }

                        $roundTeams = array_values($roundTeams);

                        $imatch++;

                    endforeach;

                endif;

            endwhile;

        endfor;
    }

    public function makeGroupCalendar($id_group, Request $request){
        $input = $request->all();
        $group = Group::where('id', '=', $id_group)->first();
        self::make_calendar($group);
        return response()->json(array('status'=>'ok'));
    }

    public static function make_macro_groups($num_gironi, $id_division){

        Group::where('id_division', '=', $id_division)->delete();

        $division = Division::where('id', '=', $id_division)->first();
        $subscriptions_list = MacroSubscription::where('id_tournament', '=', $division->id_tournament)
                                        ->where('id_zone', '=', $division->id_zone)
                                        ->where('id_category', '=', $division->id_category)
                                        ->where('id_category_type', '=', $division->id_category_type)
                                        ->get()
                                        ;

        $subscriptions = [];

        foreach($subscriptions_list as $subscription):
            $subscriptions[] = $subscription->id_team;
        endforeach;

        $num_squadre_girone = count($subscriptions) / $num_gironi;
        if($num_squadre_girone <= 0) $num_squadre_girone = 1;

        $groupName = 'A';

        if( count($subscriptions) > 0){
            for($i=1;$i<=$num_gironi;$i++){

                $group = new Group;
                $group->id_division = $division->id;
                $group->name = $groupName;
                $group->save();

                for($j=1; $j<=$num_squadre_girone; $j++){

                    $idTeamRand = rand(0, count($subscriptions)-1);

                    $groupTeam = new GroupMacroTeam;
                    $groupTeam->id_group = $group->id;
                    $groupTeam->id_team = $subscriptions[ $idTeamRand ];
                    $groupTeam->save();

                    unset($subscriptions[$idTeamRand]);
                    $subscriptions = array_values($subscriptions);

                }

                //self::make_macro_calendar($group);

                $groupName++;
            }
        }

        $division->generated = 1;
        $division->save();
    }

    private static function matchExists($id_group, $id_team1, $id_team2, $matches){

        $cur_match = [];
        $cur_match[] = $id_team1;
        $cur_match[] = $id_team2;
        sort($cur_match);

        $cur_match = implode('-', $cur_match);

        if(!empty($matches)):
            foreach($matches  as $k => $arr){
                foreach( $arr as $match){
                    sort($match);
                    $m = implode('-', $match);
                    if($m == $cur_match){
                        return true;
                    }
                }
            }
            return false;
        else:
            return false;
        endif;
    }


    public function classification($id_group){
        $classification = Classification::where('id_group', '=', $id_group)
                                        ->orderBy('points', 'desc')
                                        ->orderBy(DB::raw('games_won-games_lost'), 'DESC')
                                        ->get();

        return view('admin.groups.classification')
                ->with('classification', $classification)
                ;
    }

    public function online(Request $request){
        $input = $request->all();

        $group = Group::where('id', '=', $input['id_group'])->first();
        if( $input['flag_online'] == 'true')
            $group->flag_online = 1;
        else
            $group->flag_online = 0;
        $group->save();

        /*
        if($group->flag_online == 1):

            $groupTeams = GroupTeam::where('id_group', '=', $group->id)->get();
            foreach($groupTeams as $groupTeam):
                $teamPlayers = TeamPlayer::where('id_team', '=', $groupTeam->id_team)->get();
                foreach($teamPlayers as $player):
                    $player->player->notify(new JoinGroup($group));
                endforeach;
            endforeach;

        endif;
        */

        return response()->json(array('status'=>'OK'));
    }


    public function showEditRounds($id_group){
        $rounds = Round::where('id_group', '=', $id_group)->get();
        $groupTeams = GroupTeam::where('id_group', '=', $id_group)->get();
        $group = Group::find($id_group);
        $edition_type = $group->division->tournament->edition->edition_type;

        return view('admin.groups.editRounds')
                ->with('rounds', $rounds)
                ->with('group', $group)
                ->with('groupTeams', $groupTeams)
                ->with('edition_type', $edition_type)
                ;
    }

    public function updateRounds(Request $request)
    {
        $input = $request->all();

        if( isset($input['btn_delete_round']) ):

            Round::where('id', '=', $input['btn_delete_round'])->delete();

        elseif( isset( $input['btn_add_round'] ) ):

            $groupTeams = GroupTeam::where('id_group', '=', $input['btn_add_round'])->get();

            $round = new Round();
            $round->id_group = $input['btn_add_round'];
            $round->name = '';
            $round->description = '';
            $round->save();

            $matchcode = new Matchcode;
            $matchcode->id_ref = $round->id;
            $matchcode->ref_type = 'round';
            $matchcode->save();

            $round->matchcode = $matchcode->id;
            $round->save();

            $num_matches = round($groupTeams->count() / 2, 0);

            for($num=1; $num<=$num_matches; $num++):
                $match = new Match;
                $match->matchcode = $matchcode->id;
                $match->save();
            endfor;

        else:
            foreach($input as $k => $val ):

                preg_match('/id_round_(\d+)/', $k, $id_round);
                preg_match('/match_(\d+)_team1/', $k, $team1);
                preg_match('/match_(\d+)_team2/', $k, $team2);

                if( $id_round ):

                    $round = Round::where('id', '=', $id_round[1])->first();
                    if($round):
                        $round->description = $val;
                        $round->save();
                    endif;

                elseif( $team1 ):

                    $match = Match::find($team1[1]);
                    $match->id_team1 = $val;
                    $match->save();

                elseif( $team2 ):

                    $match = Match::find($team2[1]);
                    $match->id_team2 = $val;
                    $match->save();

                endif;
            endforeach;

            return redirect('/admin/rounds/'.$input['id_group'].'/index');

        endif;

        if( isset($input['btn_delete_round']) || isset( $input['btn_add_round'] ) ):

            $rounds = Round::where('id_group', '=', $input['id_group'])->get();
            foreach($rounds as $k => $round):
                $round->name = ($k+1);
                $round->save();
            endforeach;

        endif;

        return back()->withInput();

    }


    public function addTeam(Request $request){
        $input = $request->all();

        try{

            $team = new Team;
            $team->save();

            $team->name = 'Squadra'.$team->id;
            $team->save();

            foreach($input['players'] as $k => $id_player):
                $teamPlayer = new TeamPlayer;
                $teamPlayer->id_team = $team->id;
                $teamPlayer->id_player = $id_player;
                $teamPlayer->starter = ($k<2) ? 1 : 0;
                $teamPlayer->save();
            endforeach;

            $groupTeam = new GroupTeam;
            $groupTeam->id_group = $input['id_group'];
            $groupTeam->id_team = $team->id;
            $groupTeam->save();

            return response()->json(['status' => 'ok']);
        }catch(Exception $e){
            return response()->json(['status' => 'error']);
        }
    }

    public function removeTeam(Request $request){

        $input = $request->all();
        GroupTeam::where('id_group', '=', $input['id_group'])
                    ->where('id_team', '=', $input['id_team'])
                    ->delete();

        Classification::where('id_group', '=', $input['id_group'])
                        ->where('id_team', '=', $input['id_team'])
                        ->delete();

        return response()->json(['status' => 'ok']);

    }

    public function remove(Request $request){
        $input = $request->all();

        $id_group = $input['id_group'];

        $group = Group::where('id', '=', $id_group)->first();

        if( $group ):
            $id_division = $group->id_division;
            $group->delete();

            $division_groups = Group::where('id_division', '=', $id_division)->get();

            $name = 'A';

            foreach($division_groups as $group):
                $group->name = $name;
                $group->save();
                $name++;
            endforeach;
        endif;
        return response()->json(['status' => 'ok']);
    }

    public function addRound($id_group){

        $rounds = Round::where('id_group', '=', $id_group)->get()->toArray();

        $new_round_name = count($rounds) + 1;

        $round = new Round;
        $round->name = $new_round_name;
        $round->id_group = $id_group;
        $round->save();

        $matchcode = new Matchcode;
        $matchcode->id_ref = $round->id;
        $matchcode->ref_type = 'round';
        $matchcode->save();

        $round->matchcode = $matchcode->id;
        $round->save();

        return redirect( '/admin/rounds/' . $id_group . '/index' );
    }
}
