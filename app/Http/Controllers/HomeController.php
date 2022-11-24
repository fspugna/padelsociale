<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\News;
use App\Models\Info;
use App\Models\Edition;
use App\Models\Tournament;
use App\Models\TeamPlayer;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Division;
use App\Models\Group;
use App\Models\Round;
use App\Models\Phase;
use App\Models\Match;
use App\Models\Banner;

class HomeController extends Controller {

    public function welcome() {

        /* Post info */
        $infos = Info::where('status', '=', 1)
                ->orderBy('updated_at', 'desc')
                ->take(10)
                ->get();

        foreach ($infos as $n):
            $n['srcImgCardBig'] = asset('storage/' . $n->image);
            $n['srcImgCardBig2'] = asset('storage/' . $n->image);
        endforeach;

        /* News */
        $news = News::where('status', '=', 1)
                ->orderBy('excerpt', 'desc')
                ->orderBy(DB::raw('cast(`time` as time)'), 'DESC')
                ->orderBy('id', 'desc')
                ->take(20)
                ->get();

        foreach ($news as $n):
            $n['srcImgCardBig'] = asset('storage/' . $n->image);
            $n['srcImgCardBig2'] = asset('storage/' . $n->image);
        endforeach;

        //DB::enableQueryLog(); // Enable query log
        /** Tornei in corso */
        $tournaments = Tournament::where('date_start', '<=', Carbon::now('Europe/Rome')->format('Y-m-d'))
                ->where('date_end', '>=', Carbon::now('Europe/Rome')->format('Y-m-d'))
                ->where('id_tournament_type', '=', 1)
                ->where('generated', '=', 1)
                ->with('edition')
                ->orderBy('date_start')
                ->get();

        //dd(DB::getQueryLog()); // Show results of log
        //dd($tournaments);


        $iscritto = [];

        if (Auth::id()) {

            $my_teams = TeamPlayer::where('id_player', '=', Auth::id())->get();

            foreach ($my_teams as $team) {
                foreach ($tournaments as $tournament) {

                    $subscription = Subscription::where('id_tournament', '=', $tournament->id)
                                                ->where('id_team', '=', $team->id_team)
                                                ->first();

                    if ($subscription) {
                        if( !isset($iscritto[$tournament->id]) ):
                            $iscritto[$tournament->id] = [];
                        endif;

                        $iscritto[$tournament->id][] = $subscription->categoryType;
                    }
                }
            }
        }

        foreach ($tournaments as $k => $t):
            $tournaments[$k]['startDate'] = $t->date_start->format('Y/m/d');
            $tournaments[$k]['endDate'] = $t->date_end->format('Y/m/d');
            $tournaments[$k]['srcImgFeaturedBig'] = asset('storage/' . $t->edition->logo);
            $tournaments[$k]['srcImgFeaturedBig2'] = asset('storage/' . $t->edition->logo);

            $division = Division::where('id_tournament', '=', $t->id)
                                ->where('id_zone', $t->edition->zones->first()->id_zone)
                                ->orderBY('id_category_type', 'asc')
                                ->orderBy('id_category', 'asc')
                                ->first();

            if (empty($division))
                dd($division, $t);

            $group = Group::where('id_division', '=', $division->id)
                          ->where('flag_online', '=', 1)
                          ->first();
            //if($t->id == 84) dd($t->name , $t->id, $division, $group);

            if ($group):
                $tournaments[$k]['link'] = '/tournament/' . $t->id . '/zone/' . $division->tournament->edition->zones[0]->id_zone . '/category-type/' . $division->id_category_type . '/category/' . $division->id_category . '/group/' . $group->id . '/show';
            else:
                $tournaments[$k]['link'] = '/tournament/' . $t->id . '/zone/' . $division->tournament->edition->zones[0]->id_zone . '/category-type/' . $division->id_category_type . '/category/' . $division->id_category . '/group/0/show';
            endif;

            /*
              if($t->id_tournament_type == 1):
              $division = Division::where('id_tournament', '=', $t->id)->first();
              $group = Group::where('id_division', '=', $division->id)->where('flag_online', '=', 1)->first();
              //if($t->id == 84) dd($t->name , $t->id, $division, $group);
              if($group):
              $tournaments[$k]['link'] = '/tournament/'.$t->id.'/zone/'.$division->id_zone.'/category-type/'.$division->id_category_type.'/category/'.$division->id_category.'/group/'.$group->id.'/show';
              else:
              $tournaments[$k]['link'] = '/tournament/'.$t->id.'/zone/'.$division->id_zone.'/category-type/'.$division->id_category_type.'/category/'.$division->id_category.'/group/0/show';
              endif;
              elseif($t->id_tournament_type == 2):
              $bracket = Bracket::where('id_tournament', '=', $t->id)->where('flag_online', '=', 1)->first();
              if($bracket):
              $phase = Phase::where('id_bracket', '=', $bracket->id)->first();
              $tournaments[$k]['link'] = '/tournament/'.$t->id.'/zone/'.$bracket->id_zone.'/category-type/'.$bracket->id_category_type.'/category/'.$bracket->id_category.'/bracket/'.$bracket->id.'/phase/'.$phase->id.'/show';
              else:
              $edition = $t->edition;
              $editionZone = EditionZone::where('id_edition', '=', $edition->id)->first();
              $tournaments[$k]['link'] = '/tournament/'.$t->id.'/zone/'.$editionZone->id_zone.'/show';
              endif;
              endif;
             */
        endforeach;

        //dd($tournaments);

        $current_editions = [];
        foreach ($tournaments as $tournament):
            $current_editions[] = $tournament->id_edition;
        endforeach;

        /** Prossimi tornei */
        $next = Tournament::where('date_start', '>', Carbon::now('Europe/Rome'))
                ->where('generated', '=', 1)
                ->where('id_tournament_type', '=', 1)
                ->whereNotIn('id_edition', $current_editions)
                ->with('edition')
                ->orderBy('date_start')
                ->get();

        foreach ($next as $k => $n):
            $next[$k]['startDate'] = $n->date_start->format('Y/m/d');
            $next[$k]['endDate'] = $n->date_end->format('Y/m/d');
            $next[$k]['srcImgFeaturedBig'] = asset('storage/' . $n->edition->logo);
            $next[$k]['srcImgFeaturedBig2'] = asset('storage/' . $n->edition->logo);
            $division = Division::where('id_tournament', '=', $n->id)->orderBY('id_category_type', 'asc')->orderBy('id_category', 'asc')->first();
            $group = Group::where('id_division', '=', $division->id)->where('flag_online', '=', 1)->orderBy('name', 'asc')->first();
            if ($group):
                $next[$k]['link'] = '/tournament/' . $n->id . '/zone/' . $division->tournament->edition->zones[0]->id_zone . '/category-type/' . $division->id_category_type . '/category/' . $division->id_category . '/group/' . $group->id . '/show';
            else:
                $next[$k]['link'] = '/tournament/' . $n->id . '/zone/' . $division->tournament->edition->zones[0]->id_zone . '/category-type/' . $division->id_category_type . '/category/' . $division->id_category . '/group/0/show';
            endif;

            /*
              if($n->id_tournament_type == 1):
              $division = Division::where('id_tournament', '=', $n->id)->first();
              $group = Group::where('id_division', '=', $division->id)->where('flag_online', '=', 1)->first();
              if($group):
              $next[$k]['link'] = '/tournament/'.$n->id.'/zone/'.$division->id_zone.'/category-type/'.$division->id_category_type.'/category/'.$division->id_category.'/group/'.$group->id.'/show';
              else:
              $next[$k]['link'] = '/tournament/'.$n->id.'/zone/'.$division->id_zone.'/category-type/'.$division->id_category_type.'/category/'.$division->id_category.'/group/0/show';
              endif;
              elseif($n->id_tournament_type == 2):
              $bracket = Bracket::where('id_tournament', '=', $n->id)->where('flag_online', '=', 1)->first();
              if($bracket):
              $phase = Phase::where('id_bracket', '=', $bracket->id)->first();
              $next[$k]['link'] = '/tournament/'.$n->id.'/zone/'.$bracket->id_zone.'/category-type/'.$bracket->id_category_type.'/category/'.$bracket->id_category.'/bracket/'.$bracket->id.'/phase/'.$phase->id.'/show';
              else:
              $edition = $n->edition;
              $editionZone = EditionZone::where('id_edition', '=', $edition->id)->first();
              $next[$k]['link'] = '/tournament/'.$n->id.'/'.$n->edition->zones[0]->id.'/show';
              endif;
              endif;
             */
        endforeach;

        $tot_users = User::where('status', '=', 1)->get()->count();
        //dd($next[0]->edition);

        $matches = Match::where('date', '>=', date('Y-m-d'))
                ->whereNotNull('id_club')
                ->doesnthave('scores')
                ->orderByRaw('STR_TO_DATE(CONCAT(DATE_FORMAT(date, \'%Y-%m-%d\'), \' \', DATE_FORMAT(time, \'%H:%i\')), \'%Y-%m-%d %H:%i\')')
                ->get()
                ->take(20)
                ;

        foreach ($matches as $k => $match) {
            if ($match->matchcodes) {
                if ($match->matchcodes->ref_type == 'round') {
                    $round = Round::where('id', '=', $match->matchcodes->id_ref)->first();
                    if (!isset($round->group)) {
                        //dd($match->matchcodes, $round);
                        unset($matches[$k]);
                    }
                } elseif ($match->matchcodes->ref_type == 'phase') {
                    $phase = Phase::where('id', '=', $match->matchcodes->id_ref)->first();

                    if (!isset($phase->bracket)) {
                        //dd($match->matchcodes, $round);
                        unset($matches[$k]);
                    }
                }
            }
        }



        $matches_list = new Collection;

        $tot_matches = count($matches);
        for ($i = 1; $i <= $tot_matches; $i++) {
            $matches_list->put($i, $matches->shift());
        }

        return view('homepage')
                        ->with('infos', $infos)
                        ->with('news', $news)
                        ->with('currents', $tournaments)
                        ->with('next', $next)
                        ->with('iscritto', $iscritto)
                        ->with('tot_users', $tot_users)
                        ->with('calendar', $matches_list)
                        ;

    }



}

