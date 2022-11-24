<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Http\Requests\CreateRankingRequest;
use App\Http\Requests\UpdateRankingRequest;
use App\Repositories\RankingRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

use App\Models\Ranking;
use App\Models\User;
use App\Models\Match;
use App\Models\Score;
use App\Models\Team;
use App\Models\TeamPlayer;

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
        $res = app('App\Http\Controllers\Admin\RankingController')->classifica($gender);
        $players = [];
        foreach($res['classifica'] as $c):
            $players[$c->id_player] = User::find($c->id_player);
        endforeach;
        $params = array_merge($res, ['players' => $players, 'sel_gender' => $gender]);                
        return view('ranking', $params);
    }

    public function indexByCity($gender, $id_city)
    {        
        $res = app('App\Http\Controllers\Admin\RankingController')->classifica($gender, $id_city);
        $players = [];
        foreach($res['classifica'] as $c):
            $players[$c->id_player] = User::find($c->id_player);
        endforeach;
        $params = array_merge($res, ['players' => $players, 'sel_gender' => $gender]);                
        $params['sel_city'] = $id_city;
        return view('ranking', $params);
    }

    public function live(Request $request, $gender)
    {
        $search = '';
        if( $request->input('search') )
            $search = $request->input('search');

        if($request->has('nome')):

            /*
            $rankings = Ranking::selectRaw('sum(points) as points, id_player')
                            ->where('date', '>', Carbon::now()->subYear(1))
                            ->whereHas('player', function ($query) use($search) {
                                $query->where('name', 'like', '%'.$search.'%');
                            })
                            ->groupBy('id_player')
                            ->orderBy('points', 'DESC')
                            ->paginate(20)->appends(request()->except('page'));
                            */

            $rankings = DB::table('rankings')
                            ->join('users', 'users.id', '=', 'rankings.id_player')
                            ->selectRaw('rankings.id_player, users.name, sum(points) as points, sum(match_won) match_won, sum(match_lost) match_lost')
                            ->where('users.gender', '=', $gender)
                            ->where('users.name', 'like', '%'.$search.'%')
                            ->groupBy('rankings.id_player', 'users.name')
                            ->orderBy('points', 'DESC')
                            ->paginate(20)->appends(request()->except('page'));


        else:
            /*
            $rankings = Ranking::selectRaw('sum(points) as points, id_player')
                            ->where('date', '>', Carbon::now()->subYear(1))
                            ->groupBy('id_player')
                            ->orderBy('points', 'DESC')
                            ->paginate(20)->appends(request()->except('page'));
                            */

            $rankings = DB::table('rankings')
                            ->join('users', 'users.id', '=', 'rankings.id_player')
                            ->selectRaw('rankings.id_player, users.name, sum(points) as points, sum(match_won) match_won, sum(match_lost) match_lost')
                            ->where('users.gender', '=', $gender)
                            ->where('users.name', 'like', '%'.$search.'%')
                            ->groupBy('rankings.id_player', 'users.name')
                            //->orderBy('points', 'DESC')
                            ->orderBy('users.position', 'ASC')
                            ->paginate(20)->appends(request()->except('page'))
                            ;

        endif;

        $stats = [];

        foreach($rankings as $ranking):

            $points = isset($ranking->points) ? $ranking->points : 0;
            $match_won = isset($ranking->match_won) ? $ranking->match_won : 0;
            $match_lost = isset($ranking->match_lost) ? $ranking->match_lost : 0;

            $stats[$ranking->id_player] = ["pt"=>$points,"pv"=>$match_won,"pp"=>$match_lost];
            $player = User::where('id', '=', $ranking->id_player)->first();
            $ranking->position = $player->position;
        endforeach;

        $players = [];
        foreach($rankings as $r){
            $players[$r->id_player] = User::where('id', '=', $r->id_player)->first();
        }

        return view('ranking')
            ->with('rankings', $rankings)
            ->with('stats', $stats)
            ->with('selected_gender', $gender)
            ->with('players', $players)
            ->with('search', $search)
            ;
    }
}
