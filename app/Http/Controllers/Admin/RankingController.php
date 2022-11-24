<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Http\Requests\CreateRankingRequest;
use App\Http\Requests\UpdateRankingRequest;
use App\Repositories\RankingRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

use App\Models\Ranking;
use App\Models\Edition;
use App\Models\Event;
use App\Models\Tournament;
use App\Models\Subscription;
use App\Models\MacroSubscription;
use App\Models\MacroTeam;
use App\Models\MacroTeamPlayer;
use App\Models\Zone;
use App\Models\User;

use Carbon\Carbon;

class RankingController extends AppBaseController
{
    /** @var  RankingRepository */
    private $rankingRepository;

    public function __construct(RankingRepository $rankingRepo)
    {
        $this->rankingRepository = $rankingRepo;
    }

    /**
     * Display a listing of the Ranking.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index($gender)
    {
        $res = $this->classifica($gender);

        $params = array_merge( $res, ['sel_gender' => $gender]);

        return view('admin.rankings.index', $params);
    }

    public function classifica($gender, $id_city = null)
    {        
        $events = Event::orderBy('name')->get()->pluck('name', 'id')->toArray();

        $cur = intval(Carbon::now('Europe/Rome')->format('Y')) - 1;
        $prev = ($cur-1);

        $params = array( 'prev' => $prev-1, 'cur' => $cur-1, 'gender' => $gender) ;
        $query = "SELECT SUM(points) AS points,
                            id_player,
                            name,
                            surname
                    FROM (
                    SELECT  case when prev.points is not null then cur.points + (cur.points - prev.points) else cur.points end AS points,
                                case when prev.points is not null then cur.points - prev.points else cur.points end AS diff,
                                cur.id_event,
                                cur.id_player,
                                cur.id_club,
                                cur.id_city,
                                cur.points AS cur_points,
                                prev.points AS prev_points,
                                u.name,
                                u.surname,
                                clubs.name circolo,
                                c.name AS city
                    FROM rankings cur
                    LEFT JOIN ( SELECT id_event,
                                                id_player,
                                                points AS points
                                    FROM rankings
                                    WHERE YEAR = :prev) AS prev ON ( cur.id_event = prev.id_event AND cur.id_player = prev.id_player)
                    LEFT JOIN users u ON cur.id_player = u.id
                    LEFT JOIN clubs ON clubs.id = cur.id_club
                    LEFT JOIN cities c ON cur.id_city = c.id
                    WHERE cur.YEAR = :cur AND u.gender = :gender";
                   
        if( !empty($id_city) ){
            $query .= " AND cur.id_city = :id_city";
            $params['id_city'] = $id_city;
        }
                   
        $query .= ") AS a
                    GROUP BY id_player
                    ORDER BY 1 DESC, surname, name";

        $classifica_prev = DB::select($query, $params);

        $classifica_prec = [];

        if( $classifica_prev ) {
            foreach( $classifica_prev as $i => $c ):
                $classifica_prec[$c->id_player] = $i+1;
            endforeach;
        }

        unset($classifica_prev);        

        $params = array( 'prev' => $prev, 'cur' => $cur, 'gender' => $gender) ;
        $query = "SELECT SUM(points) AS points,
                            id_player,
                            name,
                            surname
                    FROM (
                    SELECT case when prev.points is not null then cur.points + (cur.points - prev.points) else cur.points end AS points,
                                case when prev.points is not null then cur.points - prev.points else cur.points end AS diff,
                                cur.id_event,
                                cur.id_player,
                                cur.id_club,
                                cur.id_city,
                                cur.points AS cur_points,
                                prev.points AS prev_points,
                                u.name,
                                u.surname,
                                clubs.name circolo,
                                c.name AS city
                    FROM rankings cur
                    LEFT JOIN ( SELECT id_event,
                                                id_player,
                                                points AS points
                                    FROM rankings
                                    WHERE YEAR = :prev) AS prev ON ( cur.id_event = prev.id_event AND cur.id_player = prev.id_player)
                    LEFT JOIN users u ON cur.id_player = u.id
                    LEFT JOIN clubs ON clubs.id = cur.id_club
                    LEFT JOIN cities c ON cur.id_city = c.id
                    WHERE cur.YEAR = :cur AND u.gender = :gender";
                   
        if( !empty($id_city) ){
            $query .= " AND cur.id_city = :id_city";
            $params['id_city'] = $id_city;
        }
                   
        $query .= ") AS a
                    GROUP BY id_player
                    ORDER BY 1 DESC, surname, name";                            

        $classifica = DB::select($query, $params);

        $positions = [];
        $pos = 1;
        $prev = null;
        foreach($classifica as $i => $c):
            if( $i === 0  ):
                $positions[$c->points] = $pos;
            else:
                if( $prev !== $c->points ):
                    $pos++;
                    $positions[$c->points] = $pos;
                endif;
            endif;
            $prev = $c->points;
        endforeach;

        $params = array( 'prev' => $prev, 'cur' => $cur, 'gender' => $gender) ;
        $query = "SELECT  cur.points + (cur.points - IFNULL(prev.points, 0)) AS points,
                        (cur.points - IFNULL(prev.points, 0)) AS diff,
                        cur.id_event,
                        cur.id_player,
                        cur.id_club AS cur_id_club,
                        cur.id_city AS cur_id_city,
                        cur.points AS cur_points,
                        prev.points AS prev_points,
                        u.name,
                        u.surname,
                        clubs.name circolo,
                        c.name AS city
                FROM rankings cur
                LEFT JOIN ( SELECT id_event,
                                        id_player,
                                        points AS points
                            FROM rankings
                            WHERE YEAR = :prev) AS prev ON ( cur.id_event = prev.id_event AND cur.id_player = prev.id_player)
                LEFT JOIN users u ON cur.id_player = u.id
                LEFT JOIN clubs ON clubs.id = cur.id_club
                LEFT JOIN cities c ON cur.id_city = c.id
                WHERE cur.YEAR = :cur AND u.gender = :gender";

        if( !empty($id_city) ){
            $query .= " AND cur.id_city = :id_city";
            $params['id_city'] = $id_city;
        }                                                             

        $dettEventi = DB::select($query, $params);

        $dett = [];

        foreach( $dettEventi as $de ):
            if( !isset($dett[$de->id_player]) ){
                $dett[$de->id_player] = [];
                if( !isset($dett[$de->id_player][$de->id_event]) ){
                    $dett[$de->id_player][$de->id_event] = [];
                }
            }
            $dett[$de->id_player][$de->id_event] = $de;
        endforeach;

        $params = array( 'prev' => $prev, 'cur' => $cur, 'gender' => $gender) ;
        $query = "SELECT DISTINCT cur.id_city, c.name AS city
                    FROM rankings cur
                    LEFT JOIN ( SELECT id_event,
                                            id_player,
                                            points AS points
                                FROM rankings
                                WHERE YEAR = :prev) AS prev ON ( cur.id_event = prev.id_event AND cur.id_player = prev.id_player)
                    LEFT JOIN users u ON cur.id_player = u.id
                    LEFT JOIN clubs ON clubs.id = cur.id_club
                    LEFT JOIN cities c ON cur.id_city = c.id
                    WHERE cur.YEAR = :cur AND u.gender = :gender
                    ORDER BY c.name";
        $cities = DB::select($query, $params);

        return [
            'events' => $events,
            'classifica' => $classifica,
            'classifica_prec' => $classifica_prec,
            'posizioni' => $positions,
            'dett' => $dett,
            'cities' => $cities
        ];
    }

    /*
    public function live()
    {
        $rankings = Ranking::selectRaw('sum(points) as points, id_player')
                            ->where('date', '>', Carbon::now()->subYear(1))
                            ->groupBy('id_player')
                            ->orderBy('points', 'DESC')
                            ->get();

        return view('admin.rankings.live')
            ->with('rankings', $rankings)
            ;
    }
    */

    public function assign(Request $request){

        $id_edition = $request->query('id_edition');

        $sel_edition = null;
        $edition = null;
        $subscriptions = null;

        $data = null;
        $match_played = [];
        $incontri_brackets = [];

        $editions = Edition::whereHas('tournaments', function($tournament){
                    $tournament->where('id_tournament_type', 1)
                                ->where('date_start', '<', Carbon::now())
                                ;
                })
                ->get()
                ->pluck('edition_name', 'id')
                ->toArray();

        $editions['0'] = 'Seleziona...';
        ksort($editions);

        if( !empty($id_edition) ):

            $sel_edition = $id_edition;

            $rankings = null;
            if($sel_edition):
                $rankings = Ranking::where('id_edition', $sel_edition)
                        ->get()
                        ->pluck('points', 'id_player')
                        ->toArray();

            endif;

            $edition = Edition::find($id_edition);

            /** Recupero la fase a gironi */
            $fase_a_gironi = Tournament::where('id_edition', $id_edition)
                                    ->where('id_tournament_type', 1)
                                    ->first();

            $fase_finale = Tournament::where('id_edition', $id_edition)
                                    ->where('id_tournament_type', 2)
                                    ->first();


            /** Se è un torneo singolo o doppio */
            if( in_array($edition->edition_type , [ 0, 1 ]) ):

                /** Recupero tutti gli iscritti */
                $subscriptions = Subscription::where('id_tournament', $fase_a_gironi->id)
                                                ->with('team')
                                                ->orderBy('id_zone', 'ASC')
                                                ->orderBy('id_category_type', 'ASC')
                                                ->orderBy('id_category', 'ASC')
                                                ->get();

                $gironi = DB::select("SELECT id_player, SUM(won) won, SUM(lost) lost
                                    FROM (
                                        SELECT mp.id_player, c.id_match, sum(c.won) won, sum(c.lost) lost
                                        FROM editions e
                                        JOIN tournaments t ON e.id = t.id_edition
                                        JOIN divisions d ON d.id_tournament = t.id
                                        JOIN groups g ON g.id_division = d.id
                                        JOIN rounds r ON r.id_group = g.id
                                        JOIN matchcodes mc ON mc.id = r.matchcode
                                        JOIN matches m ON m.matchcode = mc.id
                                        JOIN match_players mp ON mp.id_match = m.id
                                        JOIN classifications c ON m.id = c.id_match AND mp.id_team = c.id_team
                                        WHERE e.id = $id_edition
                                        AND t.id_tournament_type = 1
                                        GROUP BY mp.id_player, c.id_match
                                    ) a
                                    GROUP BY id_player");

                foreach($gironi as $val):
                    $match_played[$val->id_player]['Girone']['won'] = $val->won;
                    $match_played[$val->id_player]['Girone']['lost'] = $val->lost;
                endforeach;


                $tabelloni = DB::select("SELECT id_player, SUM(won) won, SUM(lost) lost
                                            FROM (
                                                SELECT distinct
                                                        id_match,
                                                        id_team1 id_team,
                                                        id_player_team1 id_player,
                                                        won_team1 won,
                                                        lost_team1 lost
                                                FROM (
                                                            SELECT distinct
                                                                id_match,
                                                                id_team1,
                                                                id_team2,
                                                                tp1.id_player id_player_team1,
                                                                tp2.id_player id_player_team2,
                                                                case when set_team1 > set_team2 then 1 ELSE 0 END won_team1,
                                                                case when set_team1 > set_team2 then 0 ELSE 1 END won_team2,
                                                                case when set_team1 > set_team2 then 0 ELSE 1 END lost_team1,
                                                                case when set_team1 > set_team2 then 1 ELSE 0 END lost_team2
                                                        FROM(
                                                        SELECT id_match,
                                                                id_team1,
                                                                id_team2,
                                                                SUM(set_1_team1 + set_2_team1 + set_3_team1 + set_4_team1 + set_5_team1) set_team1,
                                                                SUM(set_1_team2 + set_2_team2 + set_3_team2 + set_4_team2 + set_5_team2) set_team2
                                                        FROM(
                                                        SELECT id_match, id_team1, id_team2,
                                                                case when set_1_team1 > set_1_team2 then 1 ELSE 0 END set_1_team1,
                                                                case when set_1_team2 > set_1_team1 then 1 ELSE 0 END set_1_team2,
                                                                case when set_2_team1 > set_2_team2 then 1 ELSE 0 END set_2_team1,
                                                                case when set_2_team2 > set_2_team1 then 1 ELSE 0 END set_2_team2,
                                                                case when set_3_team1 > set_3_team2 then 1 ELSE 0 END set_3_team1,
                                                                case when set_3_team2 > set_3_team1 then 1 ELSE 0 END set_3_team2,
                                                                case when set_4_team1 > set_4_team2 then 1 ELSE 0 END set_4_team1,
                                                                case when set_4_team2 > set_4_team1 then 1 ELSE 0 END set_4_team2,
                                                                case when set_5_team1 > set_5_team2 then 1 ELSE 0 END set_5_team1,
                                                                case when set_5_team2 > set_5_team1 then 1 ELSE 0 END set_5_team2
                                                        FROM (
                                                        SELECT id_match,
                                                                max(id_team1) id_team1,
                                                                max(id_team2) id_team2,
                                                                MAX(set_1_team1) set_1_team1,
                                                                MAX(set_1_team2) set_1_team2,
                                                                MAX(set_2_team1) set_2_team1,
                                                                MAX(set_2_team2) set_2_team2,
                                                                MAX(set_3_team1) set_3_team1,
                                                                MAX(set_3_team2) set_3_team2,
                                                                MAX(set_4_team1) set_4_team1,
                                                                MAX(set_4_team2) set_4_team2,
                                                                MAX(set_5_team1) set_5_team1,
                                                                MAX(set_5_team2) set_5_team2
                                                                FROM (
                                                            SELECT m.id id_match,
                                                                    case when s.side='team1' then s.id_team else null END id_team1,
                                                                    case when s.side='team2' then s.id_team else null END id_team2,
                                                                    case when s.side='team1' and s.set=1 then s.`points` else null END set_1_team1,
                                                                    case when s.side='team2' and s.set=1 then s.`points` else null END set_1_team2,
                                                                    case when s.side='team1' and s.set=2 then s.`points` else null END set_2_team1,
                                                                    case when s.side='team2' and s.set=2 then s.`points` else null END set_2_team2,
                                                                    case when s.side='team1' and s.set=3 then s.`points` else null END set_3_team1,
                                                                    case when s.side='team2' and s.set=3 then s.`points` else null END set_3_team2,
                                                                    case when s.side='team1' and s.set=4 then s.`points` else null END set_4_team1,
                                                                    case when s.side='team2' and s.set=4 then s.`points` else null END set_4_team2,
                                                                    case when s.side='team1' and s.set=5 then s.`points` else null END set_5_team1,
                                                                    case when s.side='team2' and s.set=5 then s.`points` else null END set_5_team2
                                                            FROM editions e
                                                            JOIN tournaments t ON e.id = t.id_edition
                                                            JOIN brackets b ON b.id_tournament = t.id
                                                            JOIN phases p ON p.id_bracket = b.id
                                                            JOIN matchcodes mc ON mc.id = p.matchcode
                                                            JOIN matches m ON m.matchcode = mc.id
                                                            JOIN match_players mp ON mp.id_match = m.id
                                                            JOIN scores s ON m.id = s.id_match AND s.id_team = mp.id_team
                                                            WHERE e.id = $sel_edition
                                                            AND t.id_tournament_type = 2
                                                            ) a
                                                            GROUP BY a.id_match
                                                        ) b
                                                        ) c
                                                        GROUP BY id_match, id_team1, id_team2
                                                        ) d
                                                        LEFT JOIN team_players tp1 ON tp1.id_team = d.id_team1
                                                        LEFT JOIN team_players tp2 ON tp2.id_team = d.id_team2
                                                ) u1
                                                UNION ALL
                                                SELECT distinct
                                                        id_match,
                                                        id_team2,
                                                        id_player_team2,
                                                        won_team2,
                                                        lost_team2
                                                FROM (
                                                        SELECT distinct
                                                                        id_match,
                                                                        id_team1,
                                                                        id_team2,
                                                                        tp1.id_player id_player_team1,
                                                                        tp2.id_player id_player_team2,
                                                                        case when set_team1 > set_team2 then 1 ELSE 0 END won_team1,
                                                                        case when set_team1 > set_team2 then 0 ELSE 1 END won_team2,
                                                                        case when set_team1 > set_team2 then 0 ELSE 1 END lost_team1,
                                                                        case when set_team1 > set_team2 then 1 ELSE 0 END lost_team2
                                                                FROM(
                                                                SELECT id_match,
                                                                        id_team1,
                                                                        id_team2,
                                                                        SUM(set_1_team1 + set_2_team1 + set_3_team1 + set_4_team1 + set_5_team1) set_team1,
                                                                        SUM(set_1_team2 + set_2_team2 + set_3_team2 + set_4_team2 + set_5_team2) set_team2
                                                                FROM(
                                                                SELECT id_match, id_team1, id_team2,
                                                                        case when set_1_team1 > set_1_team2 then 1 ELSE 0 END set_1_team1,
                                                                        case when set_1_team2 > set_1_team1 then 1 ELSE 0 END set_1_team2,
                                                                        case when set_2_team1 > set_2_team2 then 1 ELSE 0 END set_2_team1,
                                                                        case when set_2_team2 > set_2_team1 then 1 ELSE 0 END set_2_team2,
                                                                        case when set_3_team1 > set_3_team2 then 1 ELSE 0 END set_3_team1,
                                                                        case when set_3_team2 > set_3_team1 then 1 ELSE 0 END set_3_team2,
                                                                        case when set_4_team1 > set_4_team2 then 1 ELSE 0 END set_4_team1,
                                                                        case when set_4_team2 > set_4_team1 then 1 ELSE 0 END set_4_team2,
                                                                        case when set_5_team1 > set_5_team2 then 1 ELSE 0 END set_5_team1,
                                                                        case when set_5_team2 > set_5_team1 then 1 ELSE 0 END set_5_team2
                                                                FROM (
                                                                SELECT id_match,
                                                                        max(id_team1) id_team1,
                                                                        max(id_team2) id_team2,
                                                                        MAX(set_1_team1) set_1_team1,
                                                                        MAX(set_1_team2) set_1_team2,
                                                                        MAX(set_2_team1) set_2_team1,
                                                                        MAX(set_2_team2) set_2_team2,
                                                                        MAX(set_3_team1) set_3_team1,
                                                                        MAX(set_3_team2) set_3_team2,
                                                                        MAX(set_4_team1) set_4_team1,
                                                                        MAX(set_4_team2) set_4_team2,
                                                                        MAX(set_5_team1) set_5_team1,
                                                                        MAX(set_5_team2) set_5_team2
                                                                        FROM (
                                                                    SELECT m.id id_match,
                                                                            case when s.side='team1' then s.id_team else null END id_team1,
                                                                            case when s.side='team2' then s.id_team else null END id_team2,
                                                                            case when s.side='team1' and s.set=1 then s.`points` else null END set_1_team1,
                                                                            case when s.side='team2' and s.set=1 then s.`points` else null END set_1_team2,
                                                                            case when s.side='team1' and s.set=2 then s.`points` else null END set_2_team1,
                                                                            case when s.side='team2' and s.set=2 then s.`points` else null END set_2_team2,
                                                                            case when s.side='team1' and s.set=3 then s.`points` else null END set_3_team1,
                                                                            case when s.side='team2' and s.set=3 then s.`points` else null END set_3_team2,
                                                                            case when s.side='team1' and s.set=4 then s.`points` else null END set_4_team1,
                                                                            case when s.side='team2' and s.set=4 then s.`points` else null END set_4_team2,
                                                                            case when s.side='team1' and s.set=5 then s.`points` else null END set_5_team1,
                                                                            case when s.side='team2' and s.set=5 then s.`points` else null END set_5_team2
                                                                    FROM editions e
                                                                    JOIN tournaments t ON e.id = t.id_edition
                                                                    JOIN brackets b ON b.id_tournament = t.id
                                                                    JOIN phases p ON p.id_bracket = b.id
                                                                    JOIN matchcodes mc ON mc.id = p.matchcode
                                                                    JOIN matches m ON m.matchcode = mc.id
                                                                    JOIN match_players mp ON mp.id_match = m.id
                                                                    JOIN scores s ON m.id = s.id_match AND s.id_team = mp.id_team
                                                                    WHERE e.id = $sel_edition
                                                                    AND t.id_tournament_type = 2
                                                                    ) a
                                                                    GROUP BY a.id_match
                                                                ) b
                                                                ) c
                                                                GROUP BY id_match, id_team1, id_team2
                                                                ) d
                                                                LEFT JOIN team_players tp1 ON tp1.id_team = d.id_team1
                                                                LEFT JOIN team_players tp2 ON tp2.id_team = d.id_team2
                                                ) u2
                                            ) tot
                                            GROUP BY id_player
                                            ORDER BY 2 DESC");

                foreach($tabelloni as $val):
                    $match_played[$val->id_player]['Tabellone']['won'] = $val->won;
                    $match_played[$val->id_player]['Tabellone']['lost'] = $val->lost;
                endforeach;

                $data = [];
                foreach($subscriptions as $subscription):
                    if( isset($subscription->category->name )):
                        $row = [];
                        $row['id_zone'] = $subscription->id_zone;
                        $row['zona'] = $subscription->zone->name . ' - ' . $subscription->zone->city->country->name . ' - ' . $subscription->zone->city->name;

                        $row['id_category'] = $subscription->id_category;

                        $row['category'] = $subscription->category->name;

                        $row['id_category_type'] = $subscription->id_category_type;
                        $row['category_type'] = $subscription->categoryType->name;

                        foreach($subscription->team->players as $teamPlayer):
                            $row['id_player'] = $teamPlayer->id_player;
                            $row['player_name'] = $teamPlayer->player->name . ' ' . $teamPlayer->player->surname;

                            if( isset($match_played[$teamPlayer->id_player]['Girone']) ):
                                $row['Girone_won'] = $match_played[$teamPlayer->id_player]['Girone']['won'];
                                $row['Girone_lost'] = $match_played[$teamPlayer->id_player]['Girone']['lost'];
                            else:
                                $row['Girone_won'] = null;
                                $row['Girone_lost'] = null;
                            endif;

                            if( isset($match_played[$teamPlayer->id_player]['Tabellone']) ):
                                $row['Tabellone_won'] = $match_played[$teamPlayer->id_player]['Tabellone']['won'];
                                $row['Tabellone_lost'] = $match_played[$teamPlayer->id_player]['Tabellone']['lost'];
                            else:
                                $row['Tabellone_won'] = null;
                                $row['Tabellone_lost'] = null;
                            endif;

                            $data[] = $row;

                        endforeach;
                    endif;

                endforeach;

                foreach($data as $val):

                    $incontri_brackets[$val['id_zone']][$val['id_category']][$val['id_category_type']] = null;

                    $brackets = DB::select("SELECT *
                                            FROM brackets WHERE id_tournament = " . $fase_finale->id . "
                                            AND id_zone = " . $val['id_zone'] . "
                                            AND id_category = " . $val['id_category'] . "
                                            AND id_category_type = " . $val['id_category_type']);

                    if( isset($brackets[0]) ):
                        $numero_incontri = DB::select("SELECT COUNT(*) as tot_incontri FROM matches WHERE matchcode IN (
                                                            SELECT matchcode FROM phases WHERE id IN (
                                                                SELECT MIN(id) FROM phases WHERE id_bracket = " . $brackets[0]->id . "
                                                            )
                                                        )");

                        if( isset($numero_incontri[0]) ):
                            $incontri_brackets[$val['id_zone']][$val['id_category']][$val['id_category_type']] = $numero_incontri[0]->tot_incontri;
                        endif;
                    endif;

                endforeach;

                /*
                $data->sortByDesc('Tabellone_won')
                            ->sortBy('id_category_type')
                            ->sortBy('id_category')
                            ->sortBy('id_zone');
                            */

                return view('admin.rankings.assign')->with('editions', $editions)
                                ->with('selected_edition', $sel_edition)
                                ->with('edition', $edition)
                                ->with('data', $data)
                                ->with('incontri_brackets', $incontri_brackets)
                                ->with('rankings', $rankings)
                                ;

            elseif( in_array($edition->edition_type , [ 2 ]) ):

                /** Recupero le squadre iscritte */
                /*
                $subscriptions = DB::select("SELECT mt.name, id_team, z.name, c.name category, ct.name categoryType
                                            FROM macro_subscriptions ms
                                            JOIN tournaments t ON t.id = ms.id_tournament
                                            JOIN editions e ON e.id = t.id_edition
                                            JOIN macro_teams mt ON mt.id = ms.id_team
                                            JOIN categories c ON c.id = ms.id_category
                                            JOIN category_types ct ON ct.id = ms.id_category_type
                                            JOIN zones z ON z.id = ms.id_zone
                                            WHERE e.id = :id_edition
                                            ORDER BY 4, 5", array("id_edition" => $id_edition));

                dump($subscriptions);
                */

                /** Recupero tutti gli iscritti */
                $subscriptions = MacroSubscription::where('id_tournament', $fase_a_gironi->id)
                                            ->orderBy('id_zone', 'ASC')
                                            ->orderBy('id_category_type', 'ASC')
                                            ->orderBy('id_category', 'ASC')
                                            ->get();

                $gironi = DB::select("SELECT id_team, ref_type, SUM(won) won, SUM(lost) lost FROM
                                    (
                                        SELECT distinct mtp.id_team, mt.name,
                                            mc.ref_type,
                                            c.id_match,
                                            case when ( SUM(set_won) > SUM(set_lost) ) then 1 ELSE 0 end won,
                                            case when ( SUM(set_won) < SUM(set_lost) ) then 1 ELSE 0 end lost
                                        FROM macro_scores ms
                                        LEFT JOIN macro_matches mm ON ms.id_match = mm.id
                                        LEFT JOIN matchcodes mc ON mm.matchcode = mc.id
                                        LEFT JOIN macro_team_players mtp ON ms.id_team = mtp.id_team
                                        JOIN macro_teams mt ON mtp.id_team = mt.id
                                        LEFT JOIN users u ON mtp.id_player = u.id
                                        LEFT JOIN macro_classifications c ON mtp.id_team = c.id_team and mm.id = c.id_match
                                        WHERE ms.id_team IN (
                                            SELECT distinct s.id_team
                                            FROM editions e
                                            JOIN tournaments t ON t.id_edition = e.id
                                            LEFT JOIN macro_subscriptions s ON s.id_tournament = t.id
                                            WHERE e.id = :id_edition and t.id = :id_tournament
                                        )
                                        GROUP BY mtp.id_team, mt.name, mc.ref_type, c.id_match
                                    ) a
                                    GROUP BY a.id_team, a.ref_type
                                    ORDER BY a.id_team", array('id_edition' => $id_edition, 'id_tournament' => $fase_a_gironi->id));

                foreach($gironi as $val):
                    $match_played[$val->id_team]['Girone']['won'] = $val->won;
                    $match_played[$val->id_team]['Girone']['lost'] = $val->lost;
                endforeach;


                $brackets = DB::select("SELECT b.id
                                        FROM
                                        editions e
                                        JOIN tournaments t ON t.id_edition = e.id
                                        JOIN brackets b ON b.id_tournament = t.id
                                        JOIN phases p ON p.id_bracket = b.id
                                        WHERE e.id = :id_edition AND t.id = :fase_finale", array('id_edition' => $id_edition, 'fase_finale' => $fase_finale->id));

                foreach($brackets as $bracket):
                    $details = app('App\Http\Controllers\Admin\BracketController')->macroBracketDetails($bracket->id);
                    foreach($details['allMacroScores'] as $id_macro_team => $macroScore):
                        $winner = app('App\Http\Controllers\Admin\ScoreController')->winner($macroScore);
                        $loser =  app('App\Http\Controllers\Admin\ScoreController')->loser($macroScore);

                        if( $winner && $loser ){
                            if( !isset($match_played[$winner]['Tabellone']) ){
                                $match_played[$winner]['Tabellone']['won'] = 1;
                                $match_played[$winner]['Tabellone']['lost'] = 0;
                            }else{
                                $match_played[$winner]['Tabellone']['won'] += 1;
                            }

                            if( !isset($match_played[$loser]['Tabellone']) ){
                                $match_played[$loser]['Tabellone']['won'] = 0;
                                $match_played[$loser]['Tabellone']['lost'] = 1;
                            }else{
                                $match_played[$loser]['Tabellone']['lost'] += 1;
                            }
                        }
                    endforeach;
                endforeach;

                $data = [];
                foreach($subscriptions as $subscription):
                    if( isset($subscription->category->name )):
                        $row = [];
                        $row['id_zone'] = $subscription->id_zone;
                        $row['zona'] = $subscription->zone->name . ' - ' . $subscription->zone->city->country->name . ' - ' . $subscription->zone->city->name;

                        $row['id_category'] = $subscription->id_category;

                        $row['category'] = $subscription->category->name;

                        $row['id_category_type'] = $subscription->id_category_type;
                        $row['category_type'] = $subscription->categoryType->name;

                        $row['id_macro_team'] = $subscription->team->id;
                        $row['macro_team_name'] = $subscription->team->name;

                        if( isset($match_played[$subscription->team->id]['Girone']) ):
                            $row['Girone_won'] = $match_played[$subscription->team->id]['Girone']['won'];
                            $row['Girone_lost'] = $match_played[$subscription->team->id]['Girone']['lost'];
                        else:
                            $row['Girone_won'] = null;
                            $row['Girone_lost'] = null;
                        endif;

                        if( isset($match_played[$subscription->team->id]['Tabellone']) ):
                            $row['Tabellone_won'] = $match_played[$subscription->team->id]['Tabellone']['won'];
                            $row['Tabellone_lost'] = $match_played[$subscription->team->id]['Tabellone']['lost'];
                        else:
                            $row['Tabellone_won'] = null;
                            $row['Tabellone_lost'] = null;
                        endif;

                        $data[] = $row;
                    endif;

                endforeach;

                $macro_teams_players = [];

                foreach($data as $val):

                    $incontri_brackets[$val['id_zone']][$val['id_category']][$val['id_category_type']] = null;

                    $brackets = DB::select("SELECT *
                                            FROM brackets WHERE id_tournament = " . $fase_finale->id . "
                                            AND id_zone = " . $val['id_zone'] . "
                                            AND id_category = " . $val['id_category'] . "
                                            AND id_category_type = " . $val['id_category_type']);

                    if( isset($brackets[0]) ):
                        $numero_incontri = DB::select("SELECT COUNT(*) as tot_incontri FROM matches WHERE matchcode IN (
                                                            SELECT matchcode FROM phases WHERE id IN (
                                                                SELECT MIN(id) FROM phases WHERE id_bracket = " . $brackets[0]->id . "
                                                            )
                                                        )");

                        if( isset($numero_incontri[0]) ):
                            $incontri_brackets[$val['id_zone']][$val['id_category']][$val['id_category_type']] = $numero_incontri[0]->tot_incontri;
                        endif;
                    endif;

                    $players = MacroTeamPlayer::join('users', 'users.id', '=', 'macro_team_players.id_player')
                                            ->where('id_team', $val['id_macro_team'])
                                            ->orderBy('users.name')
                                            ->orderBy('users.surname')
                                            ->get();

                    if( !isset($macro_teams_players[$val['id_macro_team']]) ){
                        $macro_teams_players[$val['id_macro_team']] = [];
                    }

                    foreach( $players as $p ):
                        $macro_teams_players[$val['id_macro_team']][] = $p->player;
                    endforeach;

                endforeach;

                return view('admin.rankings.assign_macro')->with('editions', $editions)
                                            ->with('selected_edition', $sel_edition)
                                            ->with('edition', $edition)
                                            ->with('data', $data)
                                            ->with('incontri_brackets', $incontri_brackets)
                                            ->with('rankings', $rankings)
                                            ->with('macro_teams_players', $macro_teams_players)
                                            ;

            endif;


        endif;

        return view('admin.rankings.assign')->with('editions', $editions)
                                ->with('selected_edition', $sel_edition)
                                ->with('edition', $edition)
                                ;

    }

    public function store(Request $request){
        $input = $request->except("_token");

        $id_edition = $input["id_edition"];
        unset($input["id_edition"]);        

        $edition = Edition::find($id_edition);
        $id_event = $edition->id_event;

        if( !empty($id_edition) ):

            $tournament = Tournament::where('id_edition', $id_edition)
                                ->where('id_tournament_type', 1)
                                ->first();

            foreach($input as $player => $points):
                if( !empty($points) ):
                    $arr_player = explode("_", $player);
                    $id_player = $arr_player[3];
                    $player = User::find($id_player);
                    $id_club = $player->id_club;

                    $id_zone = $arr_player[0];
                    $zone = Zone::find($id_zone);
                    $id_city = $zone->id_city;

                    Ranking::where('id_edition', $id_edition)
                            ->where('id_player', $id_player)
                            ->delete();

                    $new_row = [
                        'id_event'   => $id_event,
                        'id_edition' => $id_edition,
                        'id_player'  => $id_player,
                        'id_club'    => $id_club,
                        'id_city'    => $id_city,
                        'year'       => $tournament->date_start->format('Y'),
                        'points'     => $points
                    ];                    

                    Ranking::create($new_row);
                endif;
            endforeach;
        endif;

        return redirect(route('admin.rankings.assign'));

    }

    public function storeMacro(Request $request){
        $input = $request->except("_token");
        $id_edition = $input["id_edition"];
        unset($input["id_edition"]);

        $edition = Edition::find($id_edition);
        $id_event = $edition->id_event;

        if( !empty($id_edition) ):

            $tournament = Tournament::where('id_edition', $id_edition)
                                ->where('id_tournament_type', 1)
                                ->first();


            foreach($input as $macroTeam => $points):
                if( !empty($points) ):
                    $arr_teams = explode("_", $macroTeam);
                    $id_macro_team = $arr_teams[3];

                    $macro_team = MacroTeam::find($id_macro_team);

                    foreach($macro_team->players as $macroTeamPlayer):

                        $player = $macroTeamPlayer->player;

                        if( $player ):

                            $id_zone = $arr_teams[0];
                            $zone = Zone::find($id_zone);
                            $id_city = $zone->id_city;

                            Ranking::where('id_edition', $id_edition)
                                    ->where('id_player', $player->id)
                                    ->delete();

                            Ranking::create([
                                'id_event'   => $id_event,
                                'id_edition' => $id_edition,
                                'id_player'  => $player->id,
                                'id_club'    => $player->id_club,
                                'id_city'    => $id_city,
                                'year'       => $tournament->date_start->format('Y'),
                                'points'     => $points
                            ]);
                        endif;
                    endforeach;
                endif;
            endforeach;
        endif;

        return redirect(route('admin.rankings.assign'));
    }
}
