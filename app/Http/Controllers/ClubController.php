<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\User;
use App\Models\City;
use App\Models\Club;
use App\Models\TeamPlayer;
use App\Models\UserMetaItem;
use App\Models\UserClub;
use App\Models\Ranking;

class ClubController extends Controller
{

    public function index(Request $request){
        $search = "";
        if($request->has('search')):

            $search = $request->input('search');

            $arr_clubs = Club::where('name', 'like', '%'.$search.'%')
                             ->whereHas('user', function ($query) {
                                $query->where('status', '=', 1);
                            })
                            ->orWhere('address', 'like', '%'.$search.'%')
                            ->whereHas('user', function ($query) {
                                $query->where('status', '=', 1);
                            })
                            ->orderBy('name', 'ASC')
                            ->paginate(20)->appends(request()->except('page'));

        else:

            $arr_clubs = Club::whereHas('user', function ($query) {
                                $query->where('status', '=', 1);
                            })->orderBy('name', 'ASC')
                            ->paginate(20)
                            ->appends(request()->except('page'));

        endif;

        $cities = City::orderBy('name', 'ASC')->get();

        return view('archive-circoli')
                    ->with('clubs', $arr_clubs)
                    ->with('cities', $cities)
                    ->with('selected_city', '')
                    ->with('selected_city_id', null)
                    ->with('search', $search)
                    ;

    }

    public function show($id_club){
        $club = Club::where('id', '=', $id_club)->first();
        if( $club && isset($club->id_user) ){
            $user = User::where('id', '=', $club->id_user)->first();
            $metas = [];
            foreach($user->metas as $meta):
                $metas[$meta->meta] = $meta->meta_value;
            endforeach;

            return view('single-club')
                        ->with('club', $club)
                        ->with('user', $user)
                        ->with('metas', $metas)
                        ->with('selected_city_id', null)
                        ;
        }else{
            return redirect(route('welcome'));
        }
    }

    public function showByCity(Request $request, $slug_city){

        $city_sel = null;
        $selected_city_id = null;
        if( !empty($city_name) ):
            $city_sel = City::where('slug', '=', $slug_city)->first();
            $selected_city_id = $city_sel->id;
        endif;

        $search = "";
        if($request->has('search')):
            $search = $request->input('search');
            $clubs = Club::whereHas('user', function ($query) {
                                $query->where('status', '=', 1);
                            })
                            ->whereHas('city', function ($query) use ($slug_city)  {
                                $query->where('slug', '=', $slug_city);
                            })
                            ->where(function($query) use ($search){
                                $query->where('name', 'like', '%'.$search.'%');
                                $query->orWhere('address', 'like', '%'.$search.'%');

                            })
                            ->orderBy('name', 'ASC')
                            ->paginate(20)->appends(request()->except('page'));

        else:

            $clubs = Club::whereHas('user', function ($query) {
                                $query->where('status', '=', 1);
                            })
                            ->whereHas('city', function ($query) use ($slug_city)  {
                                $query->where('slug', '=', $slug_city);
                            })
                            ->orderBy('name', 'ASC')
                            ->paginate(20)->appends(request()->except('page'));

        endif;

        $cities = City::orderBy('name', 'ASC')->get();

        return view('archive-circoli')
                    ->with('clubs', $clubs)
                    ->with('cities', $cities)
                    ->with('selected_city', $slug_city)
                    ->with('selected_city_id', $selected_city_id)
                    ->with('search', $search)
                    ;

    }
}

