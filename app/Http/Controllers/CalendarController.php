<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Collection;
use App\Http\Requests\CreateRankingRequest;
use App\Http\Requests\UpdateRankingRequest;
use App\Repositories\RankingRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use Auth;

use App\Models\Ranking;
use App\Models\User;
use App\Models\Match;
use App\Models\Score;
use App\Models\Team;
use App\Models\TeamPlayer;
use App\Models\Round;
use App\Models\Phase;
use App\Models\City;
use App\Models\Edition;
use App\Models\Tournament;
use App\Models\Division;
use App\Models\Group;
use App\Models\Club;
use App\Models\MatchPlayer;

use Carbon\Carbon;

class CalendarController extends AppBaseController
{
    /** @var  RankingRepository */
    private $rankingRepository;

    public function __construct(RankingRepository $rankingRepo)
    {
        $this->rankingRepository = $rankingRepo;
    }

    private function formatMonth($data){
        $data = str_replace('Jan', 'Gennaio', $data);
        $data = str_replace('Feb', 'Febbraio', $data);
        $data = str_replace('Mar', 'Marzo', $data);
        $data = str_replace('Apr', 'Aprile', $data);
        $data = str_replace('May', 'Maggio', $data);
        $data = str_replace('Jun', 'Giu', $data);
        $data = str_replace('Jul', 'Luglio', $data);
        $data = str_replace('Aug', 'Agosto', $data);
        $data = str_replace('Sep', 'Settembre', $data);
        $data = str_replace('Oct', 'Ottobre', $data);
        $data = str_replace('Nov', 'Novembre', $data);
        $data = str_replace('Dec', 'Dicembre', $data);

        return $data;
    }

    public function calendar($id_event, Request $request)
    {
        /*
        $matches_list = Match::where('date', '>=', date('Y-m-d'))
                            ->whereNotNull('id_club')
                            ->doesnthave('scores')
                            ->orderByRaw('STR_TO_DATE(CONCAT(DATE_FORMAT(date, \'%Y-%m-%d\'), \' \', DATE_FORMAT(time, \'%H:%i\')), \'%Y-%m-%d %H:%i\')')
                            ->get()
                            ;
                            */

        $matches_list = new Collection;

        $matches_dates = [];

        $edition = Edition::where('id', '=', $id_event)->first();
        foreach( $edition->tournaments as $tournament ){
            if( $tournament->id_tournament_type == 1 ){
                $divisions = $tournament->divisions;
                foreach($divisions as $division){
                    foreach($division->groups as $group){
                        foreach($group->rounds as $round){

                            $matches = Match::where('matchcode', '=', $round->matchcode)
                                            ->where('date', '>=', date('Y-m-d'))
                                            ->whereNotNull('id_club')
                                            ->doesnthave('scores')
                                            ->orderByRaw('STR_TO_DATE(CONCAT(DATE_FORMAT(date, \'%Y-%m-%d\'), \' \', DATE_FORMAT(time, \'%H:%i\')), \'%Y-%m-%d %H:%i\')')
                                            ->get();

                            if( !($matches->isEmpty()) ){
                                foreach($matches as $match){
                                    $matches_list->push( $match );
                                    $matches_dates[] = $match->date;
                                }
                            }else{

                            }
                        }
                    }
                }
            }elseif( $tournament->id_tournament_type == 2 ){
                $brackets = $tournament->brackets;
                foreach($brackets as $bracket){

                    foreach($bracket->phases as $phase){

                        $matches = Match::where('matchcode', '=', $phase->matchcode)
                                        ->where('date', '>=', date('Y-m-d'))
                                        ->whereNotNull('id_club')
                                        ->whereNotNull('id_team1')
                                        ->whereNotNull('id_team2')
                                        ->doesnthave('scores')
                                        ->orderByRaw('STR_TO_DATE(CONCAT(DATE_FORMAT(date, \'%Y-%m-%d\'), \' \', DATE_FORMAT(time, \'%H:%i\')), \'%Y-%m-%d %H:%i\')')
                                        ->get();

                        if( !($matches->isEmpty()) ){
                            foreach($matches as $match){
                                $matches_list->push( $match );
                                $matches_dates[] = $match->date;
                            }
                        }else{

                        }
                    }

                }
            }
        }

        $matches_dates = array_unique($matches_dates);
        sort($matches_dates);

        foreach($matches_dates as $k => $md){
            $matches_dates[$k] = self::formatMonth($md->format('d M Y'));
        }


        //dd($matches_list->toArray());
        //$matches_list = $this->validMatches($matches_list);

        $matches = [];
        foreach( $matches_list as $match ){
            $data = $match->date->format('d M Y');
            $data = self::formatMonth($data);
            $matches[$data][] = $match;
        }


        $cities = City::all();

        return view('calendar')
            ->with('calendar', $matches)
            ->with('matches_dates', $matches_dates)
            ->with('cities', $cities)
            ->with('clubs', [])
            ->with('id_event', $id_event)
            ;
    }


    public function calendarByCity($id_event, $slug, Request $request)
    {
        /*
        $matches_list = Match::where('date', '>=', date('Y-m-d'))
                            ->whereNotNull('id_club')
                            ->doesnthave('scores')
                            ->orderByRaw('STR_TO_DATE(CONCAT(DATE_FORMAT(date, \'%Y-%m-%d\'), \' \', DATE_FORMAT(time, \'%H:%i\')), \'%Y-%m-%d %H:%i\')')
                            ->get()
                            ;

        $matches_list = $this->validMatches($matches_list);
        */

        $matches_list = new Collection;

        $matches_dates = [];

        $edition = Edition::where('id', '=', $id_event)->first();
        foreach( $edition->tournaments as $tournament ){
            if( $tournament->id_tournament_type == 1 ){
                $divisions = $tournament->divisions;
                foreach($divisions as $division){
                    foreach($division->groups as $group){
                        foreach($group->rounds as $round){

                            $matches = Match::where('matchcode', '=', $round->matchcode)
                                            ->where('date', '>=', date('Y-m-d'))
                                            ->whereNotNull('id_club')
                                            ->doesnthave('scores')
                                            ->orderByRaw('STR_TO_DATE(CONCAT(DATE_FORMAT(date, \'%Y-%m-%d\'), \' \', DATE_FORMAT(time, \'%H:%i\')), \'%Y-%m-%d %H:%i\')')
                                            ->get();

                            if( !($matches->isEmpty()) ){
                                foreach($matches as $match){
                                    $matches_list->push( $match );
                                    $matches_dates[] = $match->date;
                                }
                            }else{

                            }
                        }
                    }
                }
            }elseif( $tournament->id_tournament_type == 2 ){
                foreach($tournament->brackets as $bracket){
                    foreach($bracket->phases as $phase){
                        $matches = Match::where('matchcode', '=', $phase->matchcode)
                                        ->where('date', '>=', date('Y-m-d'))
                                        ->whereNotNull('id_club')
                                        ->doesnthave('scores')
                                        ->orderByRaw('STR_TO_DATE(CONCAT(DATE_FORMAT(date, \'%Y-%m-%d\'), \' \', DATE_FORMAT(time, \'%H:%i\')), \'%Y-%m-%d %H:%i\')')
                                        ->get();

                        if( !($matches->isEmpty()) ){
                            foreach($matches as $match){
                                $matches_list->push( $match );
                                $matches_dates[] = $match->date;
                            }
                        }else{

                        }
                    }
                }
            }
        }

        $matches_dates = array_unique($matches_dates);
        sort($matches_dates);

        foreach($matches_dates as $k => $md){
            $matches_dates[$k] = self::formatMonth($md->format('d M Y'));
        }


        $matches = [];
        foreach( $matches_list as $match ){
            if( $match->club->city->slug == $slug ){
                $data = $match->date->format('d M Y');
                $data = self::formatMonth($data);
                $matches[$data][] = $match;
            }
        }

        $cities = City::all();
        $city = City::where('slug', '=', $slug)->first();
        $clubs = Club::where('id_city', '=', $city->id)->orderBy('name', 'asc')->get();

        //dd($clubs, $city->id);

        return view('calendar')
            ->with('calendar', $matches)
            ->with('matches_dates', $matches_dates)
            ->with('cities', $cities)
            ->with('selected_city', $slug)
            ->with('clubs', $clubs)
            ->with('id_event', $id_event)
            ;
    }


    public function calendarByClub($id_event, $slug, $id_club, Request $request)
    {
        /*
        $matches_list = Match::where('date', '>=', date('Y-m-d'))
                            ->whereNotNull('id_club')
                            ->doesnthave('scores')
                            ->where('id_club', '=', $id_club)
                            ->orderByRaw('STR_TO_DATE(CONCAT(DATE_FORMAT(date, \'%Y-%m-%d\'), \' \', DATE_FORMAT(time, \'%H:%i\')), \'%Y-%m-%d %H:%i\')')
                            ->get()
                            ;

        $matches_list = $this->validMatches($matches_list);
        */

        $matches_list = new Collection;

        $matches_dates = [];

        $edition = Edition::where('id', '=', $id_event)->first();
        foreach( $edition->tournaments as $tournament ){
            if( $tournament->id_tournament_type == 1 ){
                $divisions = $tournament->divisions;
                foreach($divisions as $division){
                    foreach($division->groups as $group){
                        foreach($group->rounds as $round){

                            $matches = Match::where('matchcode', '=', $round->matchcode)
                                            ->where('date', '>=', date('Y-m-d'))
                                            ->whereNotNull('id_club')
                                            ->where('id_club', '=', $id_club)
                                            ->doesnthave('scores')
                                            ->orderByRaw('STR_TO_DATE(CONCAT(DATE_FORMAT(date, \'%Y-%m-%d\'), \' \', DATE_FORMAT(time, \'%H:%i\')), \'%Y-%m-%d %H:%i\')')
                                            ->get();

                            if( !($matches->isEmpty()) ){
                                foreach($matches as $match){
                                    $matches_list->push( $match );
                                    $matches_dates[] = $match->date;
                                }
                            }else{

                            }
                        }
                    }
                }
            }elseif( $tournament->id_tournament_type == 2 ){
                foreach($tournament->brackets as $bracket){
                    foreach($bracket->phases as $phase){
                        $matches = Match::where('matchcode', '=', $phase->matchcode)
                                        ->where('date', '>=', date('Y-m-d'))
                                        ->whereNotNull('id_club')
                                        ->where('id_club', '=', $id_club)
                                        ->doesnthave('scores')
                                        ->orderByRaw('STR_TO_DATE(CONCAT(DATE_FORMAT(date, \'%Y-%m-%d\'), \' \', DATE_FORMAT(time, \'%H:%i\')), \'%Y-%m-%d %H:%i\')')
                                        ->get();

                        if( !($matches->isEmpty()) ){
                            foreach($matches as $match){
                                $matches_list->push( $match );
                                $matches_dates[] = $match->date;
                            }
                        }else{

                        }
                    }
                }
            }
        }

        $matches_dates = array_unique($matches_dates);
        sort($matches_dates);

        foreach($matches_dates as $k => $md){
            $matches_dates[$k] = self::formatMonth($md->format('d M Y'));
        }

        $matches = [];
        foreach( $matches_list as $match ){
            if( $match->club->city->slug == $slug ){
                $data = $match->date->format('d M Y');
                $data = self::formatMonth($data);
                $matches[$data][] = $match;
            }
        }

        $cities = City::all();

        $city = City::where('slug', '=', $slug)->first();

        $clubs = Club::where('id_city', '=', $city->id)->orderBy('name', 'asc')->get();

        //dd($clubs, $city->id);

        return view('calendar')
            ->with('calendar', $matches)
            ->with('matches_dates', $matches_dates)
            ->with('cities', $cities)
            ->with('selected_city', $slug)
            ->with('clubs', $clubs)
            ->with('selected_club', $id_club)
            ->with('id_event', $id_event)
            ;
    }

    private function validMatches($matches_list){

        $matches = $matches_list;

        foreach($matches as $k => $match){
            if($match->matchcodes){
                if($match->matchcodes->ref_type == 'round'){

                    $round = Round::where('id', '=', $match->matchcodes->id_ref)->first();
                    if( !isset($round->group) ){
                        //dd($match->matchcodes, $round);
                        unset($matches[$k]);
                    }

                }elseif($match->matchcodes->ref_type == 'phase'){

                    $phase = Phase::where('id', '=', $match->matchcodes->id_ref)->first();
                    if( !isset($phase->bracket) ){
                        //dd($match->matchcodes, $round);
                        unset($matches[$k]);
                    }

                }
            }
        }

        return $matches;

    }


    public function currents(Request $request)
    {
        /*
        $tournaments = Tournament::where('date_start', '<=', Carbon::now('Europe/Rome')->format('Y-m-d'))
                                ->where('date_end', '>=', Carbon::now('Europe/Rome')->format('Y-m-d'))
                                //->where('id_tournament_type', '=', 1)
                                ->where('generated', '=', 1)
                                ->with('edition')
                                ->orderBy('date_start')
                                ->get();


        $cities = City::all();

        return view('current-events')
            ->with('events', $tournaments)
            ->with('cities', $cities)
            ;
            */

        $editions = Edition::whereHas('tournaments', function ($query) {
                $query->where('generated', '=', 1);
                $query->where('date_start', '<=', Carbon::now('Europe/Rome')->format('Y-m-d'));
                $query->where('date_end', '>=', Carbon::now('Europe/Rome')->format('Y-m-d'));
        })->get();

        $editionsImg = [];

        foreach($editions as $k => $edition){
            $editions[$k]['srcImgFeaturedBig'] = asset('storage/' . $edition->logo );
            $editions[$k]['srcImgFeaturedBig2'] = asset('storage/'. $edition->logo );
            //$editionsImg[$edition->id] = asset('storage/' . $edition->logo );
            $t = Tournament::where('id_edition', '=', $edition->id)->where('id_tournament_type', '=', 1)->first();
            $d = Division::where('id_tournament', '=', $t->id)->first();
            $g = Group::where('id_division', '=', $d->id)->where('flag_online', '=', 1)->first();
            $editions[$k]['link'] = '/tournament/'.$t->id.'/zone/'.$d->id_zone.'/category-type/'.$d->id_category_type.'/category/'.$d->id_category.'/group/'. ( ($g) ? $g->id : '0' ).'/show';
        }

        $cities = City::all();

        return view('current-events')
            ->with('events', $editions)
            ->with('editionsImg', $editionsImg)
            ->with('cities', $cities)
            ;
    }


    public function currentsByCity($slug, Request $request)
    {
        /*
        $tournaments_list = Tournament::where('date_start', '<=', Carbon::now('Europe/Rome')->format('Y-m-d'))
                                ->where('date_end', '>=', Carbon::now('Europe/Rome')->format('Y-m-d'))
                                //->where('id_tournament_type', '=', 1)
                                ->where('generated', '=', 1)
                                ->with('edition')
                                ->orderBy('date_start')
                                ->get();

        $tournaments = [];
        $tournaments_ids = [];

        foreach($tournaments_list as $tournament){
            foreach( $tournament->edition->zones as $editionZone ){
                if($editionZone->zone->city){

                    if( $editionZone->zone->city->slug == $slug && !in_array($tournament->id, $tournaments_ids)){
                        $tournaments[] = $tournament;
                        $tournaments_ids[] = $tournament->id;
                    }
                }
            }
        }

        $cities = City::all();

        return view('current-events')
            ->with('events', $tournaments)
            ->with('cities', $cities)
            ->with('selected_city', $slug)
            ;
            */

        $editionsList = Edition::whereHas('tournaments', function ($query) {
                $query->where('generated', '=', 1);
                $query->where('date_start', '<=', Carbon::now('Europe/Rome')->format('Y-m-d'));
                $query->where('date_end', '>=', Carbon::now('Europe/Rome')->format('Y-m-d'));
        })->get();


        $editions = [];
        $editions_ids = [];
        $editionsImg = [];

        foreach($editionsList as $edition){
            foreach( $edition->zones as $editionZone ){
                if($editionZone->zone->city){
                    if( $editionZone->zone->city->slug == $slug && !in_array($edition->id, $editions_ids)){
                        $editions[] = $edition;
                        $editions_ids[] = $edition->id;
                        $editionsImg[$edition->id] = asset('storage/' . $edition->logo );
                    }
                }
            }
        }

        foreach($editions as $k => $edition){
            $editions[$k]['srcImgFeaturedBig'] = asset('storage/' . $edition->logo );
            $editions[$k]['srcImgFeaturedBig2'] = asset('storage/'. $edition->logo );

            $t = Tournament::where('id_edition', '=', $edition->id)->where('id_tournament_type', '=', 1)->first();
            $d = Division::where('id_tournament', '=', $t->id)->first();
            $g = Group::where('id_division', '=', $d->id)->where('flag_online', '=', 1)->first();
            $editions[$k]['link'] = '/tournament/'.$t->id.'/zone/'.$d->id_zone.'/category-type/'.$d->id_category_type.'/category/'.$d->id_category.'/group/'. ( ($g) ? $g->id : '0' ).'/show';
        }


        $cities = City::all();

        return view('current-events')
            ->with('events', $editions)
            ->with('editionsImg', $editionsImg)
            ->with('cities', $cities)
            ->with('selected_city', $slug)
            ;
    }

    public function next(Request $request)
    {
        /*
        $tournaments = Tournament::where('date_start', '>', Carbon::now('Europe/Rome')->format('Y-m-d'))
                                //->where('id_tournament_type', '=', 1)
                                ->where('generated', '=', 1)
                                ->with('edition')
                                ->orderBy('date_start')
                                ->get();


        $cities = City::all();

        return view('next-events')
            ->with('events', $tournaments)
            ->with('cities', $cities)
            ;
            */

        $editions = Edition::whereHas('tournaments', function ($query) {
                $query->where('generated', '=', 1);
                $query->where('id_tournament_type', '=', 1);
                $query->where('date_start', '>', Carbon::now('Europe/Rome')->format('Y-m-d'));
        })->get();

        $editionsImg = [];

        foreach($editions as $k => $edition){
            $editionsImg[$edition->id] = asset('storage/' . $edition->logo );

            foreach($edition->tournaments as $tournament){
                if($tournament->id_tournament_type == 1){
                    $editions[$k]['date_start'] = $tournament->date_start;
                }
            }
        }

        foreach($editions as $k => $edition){
            $editions[$k]['srcImgFeaturedBig'] = asset('storage/' . $edition->logo );
            $editions[$k]['srcImgFeaturedBig2'] = asset('storage/'. $edition->logo );

            //$editionsImg[$edition->id] = asset('storage/' . $edition->logo );
            $t = Tournament::where('id_edition', '=', $edition->id)->where('id_tournament_type', '=', 1)->first();
            $d = Division::where('id_tournament', '=', $t->id)->first();
            $g = Group::where('id_division', '=', $d->id)->where('flag_online', '=', 1)->first();
            $editions[$k]['link'] = '/tournament/'.$t->id.'/zone/'.$d->id_zone.'/category-type/'.$d->id_category_type.'/category/'.$d->id_category.'/group/'. ( ($g) ? $g->id : '0' ).'/show';
            $editions[$k]['startDate'] = $t->date_start->format('Y/m/d');
        }

        $cities = City::all();

        return view('next-events')
            ->with('events', $editions)
            ->with('editionsImg', $editionsImg)
            ->with('cities', $cities)
            ;

    }


    public function nextByCity($slug, Request $request)
    {
        /*
        $tournaments_list = Tournament::where('date_start', '>', Carbon::now('Europe/Rome')->format('Y-m-d'))
                                //->where('id_tournament_type', '=', 1)
                                ->where('generated', '=', 1)
                                ->with('edition')
                                ->orderBy('date_start')
                                ->get();

        $tournaments = [];
        $tournaments_ids = [];

        foreach($tournaments_list as $tournament){
            foreach( $tournament->edition->zones as $editionZone ){
                if($editionZone->zone->city){

                    if( $editionZone->zone->city->slug == $slug && !in_array($tournament->id, $tournaments_ids)){
                        $tournaments[] = $tournament;
                        $tournaments_ids[] = $tournament->id;
                    }
                }
            }
        }

        $cities = City::all();

        return view('next-events')
            ->with('events', $tournaments)
            ->with('cities', $cities)
            ->with('selected_city', $slug)
            ;
        */

    $editionsList = Edition::whereHas('tournaments', function ($query) {
            $query->where('id_tournament_type', '=', 1);
            $query->where('date_start', '>', Carbon::now('Europe/Rome')->format('Y-m-d'));
    })->get();


    $editions = [];
    $editions_ids = [];
    $editionsImg = [];

    foreach($editionsList as $edition){
        foreach( $edition->zones as $editionZone ){
            if($editionZone->zone->city){
                if( $editionZone->zone->city->slug == $slug && !in_array($edition->id, $editions_ids)){
                    $editions[] = $edition;
                    $editions_ids[] = $edition->id;
                    //$editionsImg[$edition->id] = asset('storage/' . $edition->logo );
                }
            }
        }
    }

    foreach($editions as $k => $edition){
        $editions[$k]['srcImgFeaturedBig'] = asset('storage/' . $edition->logo );
        $editions[$k]['srcImgFeaturedBig2'] = asset('storage/'. $edition->logo );
        //$editionsImg[$edition->id] = asset('storage/' . $edition->logo );
        $t = Tournament::where('id_edition', '=', $edition->id)->where('id_tournament_type', '=', 1)->first();
        $d = Division::where('id_tournament', '=', $t->id)->first();
        $g = Group::where('id_division', '=', $d->id)->where('flag_online', '=', 1)->first();
        $editions[$k]['link'] = '/tournament/'.$t->id.'/zone/'.$d->id_zone.'/category-type/'.$d->id_category_type.'/category/'.$d->id_category.'/group/'. ( ($g) ? $g->id : '0' ).'/show';
    }


    $cities = City::all();

    return view('next-events')
        ->with('events', $editions)
        ->with('editionsImg', $editionsImg)
        ->with('cities', $cities)
        ->with('selected_city', $slug)
        ;
    }

    public function old(Request $request)
    {
        /*
        $tournaments = Tournament::where('date_end', '<', Carbon::now('Europe/Rome')->format('Y-m-d'))
                                //->where('id_tournament_type', '=', 1)
                                ->where('generated', '=', 1)
                                ->with('edition')
                                ->orderBy('date_start')
                                ->get();
                                */

        $editionsList = Edition::whereHas('tournaments', function ($query) {
                $query->where('generated', '=', 1);
        })->get();

        $editions = [];

        foreach($editionsList as $edition){
            $is_old = true;
            foreach($edition->tournaments as $tournament){
                if($tournament->date_end >= Carbon::now('Europe/Rome')->format('Y-m-d')){
                    $is_old = false;
                }
            }

            if($is_old){
                $editions[] = $edition;
            }
        }

        /*
        foreach($editions as $edition){
            $editionsImg[$edition->id] = asset('storage/' . $edition->logo );
        }
        */

        foreach($editions as $k => $edition){
            $editions[$k]['srcImgFeaturedBig'] = asset('storage/' . $edition->logo );
            $editions[$k]['srcImgFeaturedBig2'] = asset('storage/'. $edition->logo );
        }

        $cities = City::all();

        return view('old-events')
            ->with('events', $editions)
            ->with('cities', $cities)
            ;
    }


    public function oldByCity($slug, Request $request)
    {
        /*
        $tournaments_list = Tournament::where('date_start', '<', Carbon::now('Europe/Rome')->format('Y-m-d'))
                                //->where('id_tournament_type', '=', 1)
                                ->where('generated', '=', 1)
                                ->with('edition')
                                ->orderBy('date_start')
                                ->get();
        */


        $editionsList = Edition::whereHas('tournaments', function ($query) {
                $query->where('generated', '=', 1);
                $query->where('date_end', '<', Carbon::now('Europe/Rome')->format('Y-m-d'));
        })->get();


        $editions = [];
        $editions_ids = [];
        $editionsImg = [];

        foreach($editionsList as $edition){

            $is_old = true;
            foreach($edition->tournaments as $tournament){
                if($tournament->date_end >= Carbon::now('Europe/Rome')->format('Y-m-d')){
                    $is_old = false;
                }
            }

            foreach( $edition->zones as $editionZone ){
                if($editionZone->zone->city){
                    if( $editionZone->zone->city->slug == $slug && !in_array($edition->id, $editions_ids) && $is_old){
                        $editions[] = $edition;
                        $editions_ids[] = $edition->id;
                        //$editionsImg[$edition->id] = asset('storage/' . $edition->logo );
                    }
                }
            }
        }

        foreach($editions as $k => $edition){
            $editions[$k]['srcImgFeaturedBig'] = asset('storage/' . $edition->logo );
            $editions[$k]['srcImgFeaturedBig2'] = asset('storage/'. $edition->logo );
            //$editionsImg[$edition->id] = asset('storage/' . $edition->logo );
        }

        $cities = City::all();

        return view('old-events')
            ->with('events', $editions)
            ->with('cities', $cities)
            ->with('selected_city', $slug)
            ;
    }


    /**************** Match giocati *****************/

    public function matchArchive($id_event, Request $request)
    {
        /*
        $matches_list = Match::where('date', '>=', date('Y-m-d'))
                            ->whereNotNull('id_club')
                            ->doesnthave('scores')
                            ->orderByRaw('STR_TO_DATE(CONCAT(DATE_FORMAT(date, \'%Y-%m-%d\'), \' \', DATE_FORMAT(time, \'%H:%i\')), \'%Y-%m-%d %H:%i\')')
                            ->get()
                            ;
                            */

        $matches_list = new Collection;

        $matches_dates = [];
        $scores = [];
        $matchPlayers = [];

        $edition = Edition::where('id', '=', $id_event)->first();
        foreach( $edition->tournaments as $tournament ){
            if( $tournament->id_tournament_type == 1 ){
                $divisions = $tournament->divisions;
                foreach($divisions as $division){
                    foreach($division->groups as $group){
                        foreach($group->rounds as $round){

                            $matches = Match::where('matchcode', '=', $round->matchcode)
                                            ->where('date', '<=', date('Y-m-d'))
                                            ->where('a_tavolino', '=', 0)
                                            ->where('id_club', '>', 0)
                                            ->has('scores')
                                            ->orderByRaw('STR_TO_DATE(CONCAT(DATE_FORMAT(date, \'%Y-%m-%d\'), \' \', DATE_FORMAT(time, \'%H:%i\')), \'%Y-%m-%d %H:%i\')')
                                            ->get();

                            if( !($matches->isEmpty()) ){
                                foreach($matches as $match){


                                    $matches_list->push( $match );
                                    $matches_dates[] = $match->date;


                                    $players_team1 = MatchPlayer::where('id_match', '=', $match->id)
                                                            ->where('id_team', '=', $match->id_team1)
                                                            ->get();

                                    foreach($players_team1 as $matchPlayer){
                                    $matchPlayers[$match->id]['team1'][] = $matchPlayer->player;
                                    }



                                    $players_team2 = MatchPlayer::where('id_match', '=', $match->id)
                                                            ->where('id_team', '=', $match->id_team2)
                                                            ->get();

                                    foreach($players_team2 as $matchPlayer){
                                    $matchPlayers[$match->id]['team2'][] = $matchPlayer->player;
                                    }

                                    foreach($match->scores as $score){

                                        if($score->id_team == $match->id_team1):
                                            $scores[$match->id][$score->set]['team1'] = $score->points;
                                        else:
                                            $scores[$match->id][$score->set]['team2'] = $score->points;
                                        endif;

                                    }
                                }
                            }else{

                            }
                        }
                    }
                }
            }
        }

        //dd($matches_dates);

        $matches_dates = array_unique($matches_dates);
        rsort($matches_dates);

        foreach($matches_dates as $k => $md){
            $matches_dates[$k] = self::formatMonth($md->format('d M Y'));
        }


        //dd($matches_list->toArray());
        //$matches_list = $this->validMatches($matches_list);

        $matches = [];
        foreach( $matches_list as $match ){
            $data = $match->date->format('d M Y');
            $data = self::formatMonth($data);
            $matches[$data][] = $match;
        }

        $cities = City::all();

        return view('archive-match')
            ->with('calendar', $matches)
            ->with('matches_dates', $matches_dates)
            ->with('cities', $cities)
            ->with('clubs', [])
            ->with('id_event', $id_event)
            ->with('scores', $scores)
            ->with('scores', $scores)
            ->with('match_players', $matchPlayers)
            ;
    }


    public function matchArchiveByCity($id_event, $slug, Request $request)
    {
        /*
        $matches_list = Match::where('date', '>=', date('Y-m-d'))
                            ->whereNotNull('id_club')
                            ->doesnthave('scores')
                            ->orderByRaw('STR_TO_DATE(CONCAT(DATE_FORMAT(date, \'%Y-%m-%d\'), \' \', DATE_FORMAT(time, \'%H:%i\')), \'%Y-%m-%d %H:%i\')')
                            ->get()
                            ;

        $matches_list = $this->validMatches($matches_list);
        */

        $matches_list = new Collection;

        $matches_dates = [];
        $scores = [];
        $matchPlayers = [];

        $edition = Edition::where('id', '=', $id_event)->first();
        foreach( $edition->tournaments as $tournament ){
            if( $tournament->id_tournament_type == 1 ){
                $divisions = $tournament->divisions;
                foreach($divisions as $division){
                    foreach($division->groups as $group){
                        foreach($group->rounds as $round){

                            $matches = Match::where('matchcode', '=', $round->matchcode)
                                            ->where('date', '<=', date('Y-m-d'))
                                            ->where('a_tavolino', '=', 0)
                                            ->whereNotNull('id_club')
                                            ->has('scores')
                                            ->orderByRaw('STR_TO_DATE(CONCAT(DATE_FORMAT(date, \'%Y-%m-%d\'), \' \', DATE_FORMAT(time, \'%H:%i\')), \'%Y-%m-%d %H:%i\')')
                                            ->get();

                            if( !($matches->isEmpty()) ){
                                foreach($matches as $match){
                                    $matches_list->push( $match );
                                    $matches_dates[] = $match->date;

                                    $players_team1 = MatchPlayer::where('id_match', '=', $match->id)
                                                            ->where('id_team', '=', $match->id_team1)
                                                            ->get();

                                    foreach($players_team1 as $matchPlayer){
                                    $matchPlayers[$match->id]['team1'][] = $matchPlayer->player;
                                    }



                                    $players_team2 = MatchPlayer::where('id_match', '=', $match->id)
                                                            ->where('id_team', '=', $match->id_team2)
                                                            ->get();

                                    foreach($players_team2 as $matchPlayer){
                                    $matchPlayers[$match->id]['team2'][] = $matchPlayer->player;
                                    }

                                    foreach($match->scores as $score){

                                        if($score->id_team == $match->id_team1):
                                            $scores[$match->id][$score->set]['team1'] = $score->points;
                                        else:
                                            $scores[$match->id][$score->set]['team2'] = $score->points;
                                        endif;

                                    }
                                }
                            }else{

                            }
                        }
                    }
                }
            }
        }

        $matches_dates = array_unique($matches_dates);
        rsort($matches_dates);

        foreach($matches_dates as $k => $md){
            $matches_dates[$k] = self::formatMonth($md->format('d M Y'));
        }

        $matches = [];
        foreach( $matches_list as $match ){
            if( $match->club->city->slug == $slug ){
                $data = $match->date->format('d M Y');
                $data = self::formatMonth($data);
                $matches[$data][] = $match;
            }
        }

        $cities = City::all();
        $city = City::where('slug', '=', $slug)->first();
        $clubs = Club::where('id_city', '=', $city->id)->orderBy('name', 'asc')->get();

        //dd($clubs, $city->id);

        return view('archive-match')
            ->with('calendar', $matches)
            ->with('matches_dates', $matches_dates)
            ->with('cities', $cities)
            ->with('selected_city', $slug)
            ->with('clubs', $clubs)
            ->with('id_event', $id_event)
            ->with('scores', $scores)
            ->with('match_players', $matchPlayers)
            ;
    }


    public function matchArchiveByClub($id_event, $slug, $id_club, Request $request)
    {
        /*
        $matches_list = Match::where('date', '>=', date('Y-m-d'))
                            ->whereNotNull('id_club')
                            ->doesnthave('scores')
                            ->where('id_club', '=', $id_club)
                            ->orderByRaw('STR_TO_DATE(CONCAT(DATE_FORMAT(date, \'%Y-%m-%d\'), \' \', DATE_FORMAT(time, \'%H:%i\')), \'%Y-%m-%d %H:%i\')')
                            ->get()
                            ;

        $matches_list = $this->validMatches($matches_list);
        */

        $matches_list = new Collection;

        $matches_dates = [];
        $scores = [];
        $matchPlayers = [];

        $edition = Edition::where('id', '=', $id_event)->first();
        foreach( $edition->tournaments as $tournament ){
            if( $tournament->id_tournament_type == 1 ){
                $divisions = $tournament->divisions;
                foreach($divisions as $division){
                    foreach($division->groups as $group){
                        foreach($group->rounds as $round){

                            $matches = Match::where('matchcode', '=', $round->matchcode)
                                            ->where('date', '<=', date('Y-m-d'))
                                            ->where('a_tavolino', '=', 0)
                                            ->whereNotNull('id_club')
                                            ->where('id_club', '=', $id_club)
                                            ->has('scores')
                                            ->orderByRaw('STR_TO_DATE(CONCAT(DATE_FORMAT(date, \'%Y-%m-%d\'), \' \', DATE_FORMAT(time, \'%H:%i\')), \'%Y-%m-%d %H:%i\')')
                                            ->get();

                            if( !($matches->isEmpty()) ){
                                foreach($matches as $match){
                                    $matches_list->push( $match );
                                    $matches_dates[] = $match->date;

                                    $players_team1 = MatchPlayer::where('id_match', '=', $match->id)
                                                            ->where('id_team', '=', $match->id_team1)
                                                            ->get();

                                    foreach($players_team1 as $matchPlayer){
                                    $matchPlayers[$match->id]['team1'][] = $matchPlayer->player;
                                    }



                                    $players_team2 = MatchPlayer::where('id_match', '=', $match->id)
                                                            ->where('id_team', '=', $match->id_team2)
                                                            ->get();

                                    foreach($players_team2 as $matchPlayer){
                                    $matchPlayers[$match->id]['team2'][] = $matchPlayer->player;
                                    }

                                    foreach($match->scores as $score){

                                        if($score->id_team == $match->id_team1):
                                            $scores[$match->id][$score->set]['team1'] = $score->points;
                                        else:
                                            $scores[$match->id][$score->set]['team2'] = $score->points;
                                        endif;

                                    }
                                }
                            }else{

                            }
                        }
                    }
                }
            }
        }

        $matches_dates = array_unique($matches_dates);
        rsort($matches_dates);

        foreach($matches_dates as $k => $md){
            $matches_dates[$k] = self::formatMonth($md->format('d M Y'));
        }

        $matches = [];
        foreach( $matches_list as $match ){
            if( $match->club->city->slug == $slug ){
                $data = $match->date->format('d M Y');
                $data = self::formatMonth($data);
                $matches[$data][] = $match;
            }
        }

        $cities = City::all();

        $city = City::where('slug', '=', $slug)->first();

        $clubs = Club::where('id_city', '=', $city->id)->orderBy('name', 'asc')->get();

        //dd($clubs, $city->id);

        return view('archive-match')
            ->with('calendar', $matches)
            ->with('matches_dates', $matches_dates)
            ->with('cities', $cities)
            ->with('selected_city', $slug)
            ->with('clubs', $clubs)
            ->with('selected_club', $id_club)
            ->with('id_event', $id_event)
            ->with('scores', $scores)
            ->with('match_players', $matchPlayers)
            ;
    }

    public function archiveMatches(Request $request){

        $input = $request->all();

        $search = null;

        $year_sel = date('Y');

        if( !isset($input['search']) || empty($input['search']) ):

            $arr_editions = Edition::whereHas('tournaments', function($query){
                                            // $query->where('id_tournament_type', '=', 2);
                                            $query->where('date_end', '<', Carbon::now('Europe/Rome')->format('Y-m-d'));
                                        })
                                    //   ->whereHas('tournaments', function($query) use($year_sel) {
                                    //         $query->where('id_tournament_type', '=', 1);
                                    //         $query->whereRaw("DATE_FORMAT(date_start, '%Y') =  ?", [$year_sel]);
                                    //     })
                                        ->orderBy('id', 'DESC')
                                        ->get();

        else:

            $search = $input['search'];

            $arr_editions = Edition::whereHas('tournaments', function($query){
                                            // $query->where('id_tournament_type', '=', 2);
                                            $query->where('date_end', '<', Carbon::now('Europe/Rome')->format('Y-m-d'));
                                            $query->whereRaw("DATE_FORMAT(date_start, '%Y') =  ?", [$year_sel]);
                                        })
                                    //   ->whereHas('tournaments', function($query) use($year_sel) {
                                    //         $query->where('id_tournament_type', '=', 1);
                                    //         $query->whereRaw("DATE_FORMAT(date_start, '%Y') =  ?", [$year_sel]);
                                    //     })
                                        ->where('edition_name', 'like', '%'.$search.'%')
                                        ->orderBy('id', 'DESC')
                                        ->get();

        endif;

        $tournaments = [];

        foreach ($arr_editions as $k => $e):

            $fase_a_gironi = Tournament::where('id_edition', '=', $e->id)->where('id_tournament_type', '=', 1)->first();

            $tournaments[$k]['edition'] = $fase_a_gironi->edition;
            $tournaments[$k]['startDate'] = $fase_a_gironi->date_start->format('Y/m/d');
            $tournaments[$k]['endDate'] = $fase_a_gironi->date_end->format('Y/m/d');
            $tournaments[$k]['srcImgFeaturedBig'] = asset('storage/' . $fase_a_gironi->edition->logo);
            $tournaments[$k]['srcImgFeaturedBig2'] = asset('storage/' . $fase_a_gironi->edition->logo);

            $division = Division::where('id_tournament', '=', $fase_a_gironi->id)
                                ->orderBy('id_zone', 'asc')
                                ->orderBY('id_category_type', 'asc')
                                ->orderBy('id_category', 'asc')
                                ->first();


            $group = Group::where('id_division', '=', $division->id)->where('flag_online', '=', 1)->first();

            if ($group):
                $tournaments[$k]['link'] = '/tournament/' . $fase_a_gironi->id . '/zone/' . $division->id_zone . '/category-type/' . $division->id_category_type . '/category/' . $division->id_category . '/group/' . $group->id . '/show';
            else:
                $tournaments[$k]['link'] = '/tournament/' . $fase_a_gironi->id . '/zone/' . $division->id_zone . '/category-type/' . $division->id_category_type . '/category/' . $division->id_category . '/group/0/show';
            endif;

        endforeach;

        $current_year = intval(date('Y'));
        $selected_year = $current_year;

        return view('archive-tournaments')->with('tournaments', $tournaments)
                                          ->with('current_year', $current_year)
                                          ->with('selected_year', $selected_year)
                                          ->with('search', $search)
                                          ;

    }

    public function archiveMatchesByYear($year_sel, Request $request){

        $input = $request->all();

        $search = null;

        if( !isset($input['search']) || empty($input['search']) ):

            $arr_editions = Edition::whereHas('tournaments', function($query){
                                            $query->where('id_tournament_type', '=', 2);
                                            $query->where('date_end', '<', Carbon::now('Europe/Rome')->format('Y-m-d'));
                                        })
                                      ->whereHas('tournaments', function($query) use($year_sel) {
                                            $query->where('id_tournament_type', '=', 1);
                                            $query->whereRaw("DATE_FORMAT(date_start, '%Y') =  ?", [$year_sel]);
                                        })
                                        ->orderBy('id', 'DESC')
                                        ->get();

        else:

            $search = $input['search'];

            $arr_editions = Edition::whereHas('tournaments', function($query){
                                            $query->where('id_tournament_type', '=', 2);
                                            $query->where('date_end', '<', Carbon::now('Europe/Rome')->format('Y-m-d'));
                                        })
                                      ->whereHas('tournaments', function($query) use($year_sel) {
                                            $query->where('id_tournament_type', '=', 1);
                                            $query->whereRaw("DATE_FORMAT(date_start, '%Y') =  ?", [$year_sel]);
                                        })
                                        ->where('edition_name', 'like', '%'.$search.'%')
                                        ->orderBy('id', 'DESC')
                                        ->get();

        endif;

        $tournaments = [];

        foreach ($arr_editions as $k => $e):

            $fase_a_gironi = Tournament::where('id_edition', '=', $e->id)->where('id_tournament_type', '=', 1)->first();

            $tournaments[$k]['edition'] = $fase_a_gironi->edition;
            $tournaments[$k]['startDate'] = $fase_a_gironi->date_start->format('Y/m/d');
            $tournaments[$k]['endDate'] = $fase_a_gironi->date_end->format('Y/m/d');
            $tournaments[$k]['srcImgFeaturedBig'] = asset('storage/' . $fase_a_gironi->edition->logo);
            $tournaments[$k]['srcImgFeaturedBig2'] = asset('storage/' . $fase_a_gironi->edition->logo);

            $division = Division::where('id_tournament', '=', $fase_a_gironi->id)
                                ->orderBy('id_zone', 'asc')
                                ->orderBY('id_category_type', 'asc')
                                ->orderBy('id_category', 'asc')
                                ->first();


            $group = Group::where('id_division', '=', $division->id)->where('flag_online', '=', 1)->first();

            if ($group):
                $tournaments[$k]['link'] = '/tournament/' . $fase_a_gironi->id . '/zone/' . $division->id_zone . '/category-type/' . $division->id_category_type . '/category/' . $division->id_category . '/group/' . $group->id . '/show';
            else:
                $tournaments[$k]['link'] = '/tournament/' . $fase_a_gironi->id . '/zone/' . $division->id_zone . '/category-type/' . $division->id_category_type . '/category/' . $division->id_category . '/group/0/show';
            endif;

        endforeach;

        $current_year = intval(date('Y'));

        return view('archive-tournaments')->with('tournaments', $tournaments)
                                          ->with('current_year', $current_year)
                                          ->with('selected_year', $year_sel)
                                          ->with('search', $search)
                                          ;

    }

}
