<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\Auth;

use App\Models\Banner;
use App\Models\Role;
use App\Models\User;
use App\Models\Tournament;
use App\Models\NewsCategory;
use App\Models\Team;
use App\Models\TeamPlayer;
use App\Models\Division;
use App\Models\Group;
use App\Models\Edition;
use App\Models\Club;
use App\Models\Partner;

use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        view()->composer('*', function ($view) {

            $avatar = null;
            $user = ['name' => 'Effettua il login'];
            $unread_notifications = null;
            $role = null;

            $current_club = null;
            $current_partner = null;

            $avatar = 'https://comps.canstockphoto.es/padel-jugador-figura-vectores-eps_csp61076734.jpg';

            if(Auth::id()):
                $user = User::find(Auth::id());
                $role = Role::find(User::find(Auth::id())->id_role);

                if( $user->id_role == 3 ):
                    $current_club = Club::where('id_user', $user->id)->first();
                endif;

                if( $user->id_role == 4 ):
                    $current_partner = Partner::where('id_user', $user->id)->first();
                endif;

                $unread_notifications = count($user->unreadNotifications);
                if($user->metas):
                    foreach($user->metas as $meta):
                        if($meta->meta == "avatar"):
                            $avatar = asset('storage/'.$meta->meta_value);
                        endif;
                    endforeach;
                endif;

                if ($avatar == ''):
                    if ($user->gender == 'f'):
                        $avatar = 'https://cdn.xl.thumbs.canstockphoto.es/padel-mujer-jugador-vectores-eps_csp69229035.jpg';
                    else:
                        $avatar = 'https://comps.canstockphoto.es/padel-jugador-figura-vectores-eps_csp61076734.jpg';
                    endif;
                endif;
            endif;

            //$show_banners = Banner::all();
            $show_banners = Banner::whereHas('partner', function($partner){
                $partner->whereHas('user', function($query){
                    $query->where('status', 1);
                });
            })->get();

            //dd($menu_tournaments);
            //dd($user);
            //dd($current_club);

            $view->with('avatar', $avatar)
                ->with('current_user', $user)
                ->with('current_club', $current_club)
                ->with('current_partner', $current_partner)
                ->with('auth_id', Auth::id())
                ->with('tot_notifications', $unread_notifications )
                ->with('role', $role)
                ->with('show_banners', $show_banners)
                ->with('version', env("APP_VERSION"));
        });

        view()->composer('components.sections.header', function ($view) {

            $my_editions = [];
            $my_editions_links = [];
            $my_editions_teams = [];

            if(Auth::check()):
                if(Auth::user()->id_role == 2): //Giocatore

                    /** Cerco tutte le mie squadre */
                    $teamPlayers = TeamPlayer::where('id_player', '=', Auth::id())->get();

                    foreach($teamPlayers as $teamPlayer):
                        foreach($teamPlayer->team->subscriptions as $subscription):
                            if($subscription->tournament->date_end->isFuture()){
                                $divisions = Division::where('id_tournament', '=', $subscription->id_tournament)
                                                        ->where('id_zone', '=', $subscription->id_zone)
                                                        //->where('id_category_type', '=', $subscription->id_category_type)
                                                        ->get();

                                foreach($divisions as $division):
                                    $groups = Group::where('id_division', '=', $division->id)->get();
                                    foreach($groups as $group):
                                        foreach($group->teams as $groupTeam):
                                            if($groupTeam->id_team == $teamPlayer->id_team):

                                                $my_editions[$subscription->tournament->id_edition][$division->id] = $division;

                                                $my_editions_links[$subscription->tournament->id_edition][$division->id] = '/tournament/'.$subscription->id_tournament.'/zone/'.$division->id_zone.'/category-type/'.$division->id_category_type.'/category/'.$division->id_category.'/group/'.$group->id.'/show';

                                                $my_editions_teams[$subscription->tournament->id_edition][$division->id] = $teamPlayer->team;

                                            endif;
                                        endforeach;
                                    endforeach;
                                endforeach;

                            }
                        endforeach;
                    endforeach;
                elseif(Auth::user()->id_role == 3): // Circolo
                    // Cerco il circolo dell'utente corrente
                    $my_club = Club::where('id_user', Auth::id())->first();

                    //Cerco tutti i tornei in corso
                    $tournaments = Tournament::where('id_tournament_type', 1)
                                            //->where('date_start', '<=', DB::raw('CURRENT_DATE()'))
                                            ->where('date_end', '>=', DB::raw('CURRENT_DATE()'))
                                            ->get();

                    // Scorro tutte le zone del torneo (Edizione)
                    // e tutti i circoli delle zone
                    // per trovare quelle del circolo corrente

                    $my_editions = [];

                    foreach($tournaments as $tournament):
                        foreach($tournament->edition->zones as $editionZone):
                            foreach($editionZone->zone->clubs as $zoneClub):
                                if( $zoneClub->club->id == $my_club->id):

                                    $divisions = Division::where('id_tournament', $tournament->id)
                                                        ->where('id_zone', $editionZone->id_zone)
                                                        ->orderBy('id_category_type')
                                                        ->orderBy('id_category')
                                                        ->get();

                                    foreach($divisions as $division):

                                        $group = Group::where('id_division', '=', $division->id)->first();

                                        $my_editions[$tournament->id_edition][$division->id] = $division;
                                        $my_editions_links[$tournament->id_edition][$division->id] = '/tournament/'.$tournament->id.'/zone/'.$division->id_zone.'/category-type/'.$division->id_category_type.'/category/'.$division->id_category.'/group/'.$group->id.'/show';
                                    endforeach;
                                endif;
                            endforeach;
                        endforeach;
                    endforeach;


                endif;
            endif;

            /*
            dump($my_editions);
            dump($my_editions_links);
            dd($my_editions_teams);
            */

            $view->with('my_editions', $my_editions)
                 ->with('my_editions_links', $my_editions_links)
                 ->with('my_editions_teams', $my_editions_teams)
                 ;
        });

        view()->composer('base', function ($view) {

            /** Creazione del menu */
            $menu = array(
                'home' => array('link' => '/', 'name' => 'Home'),
                'chisiamo' => array('link' => '/pages/chi-siamo', 'name' => 'Chi siamo'),
                'news' => array('link' => '/news', 'name' => 'News')
            );

            //DB::enableQueryLog(); // Enable query log

            $t_cities = DB::table('cities')
                        ->select(DB::raw("cities.name as city, cities.slug,  tournaments.date_start, editions.edition_name as edition_name, editions.id as id_edition, min(zones.id) id_zone"))
                        ->leftJoin('zones', 'cities.id', '=', 'zones.id_city')
                        ->leftJoin('editions_zones', 'editions_zones.id_zone', '=', 'zones.id')
                        ->leftJoin('tournaments', 'tournaments.id_edition', '=', 'editions_zones.id_edition')
                        ->leftJoin('editions', 'tournaments.id_edition', '=', 'editions.id')
                        ->whereNotNull('tournaments.id')
                        ->where('editions.id_event', 1)
                        ->where('tournaments.date_end', '>=', Carbon::now('Europe/Rome')->format('Y-m-d'))
                        ->where('tournaments.id_tournament_type', '=', 1)
                        ->where('tournaments.generated', '=', 1)
                        ->groupBy(DB::Raw('cities.name, cities.slug, tournaments.date_start, editions.edition_name, editions.id'))
                        ->orderBy('cities.name', 'ASC')
                        ->orderBy('tournaments.date_start', 'ASC')
                        ->get();

            //dd(DB::getQueryLog()); // Show results of log
            //dd($t_cities);

            $menu_tournaments = array();
            $city_tournaments = null;

            foreach($t_cities as $t):

                //$tournament = Tournament::where('id', '=', $t->id_tournament)->first();
                $edition = Edition::where('id', '=', $t->id_edition)->first();

                $zone = $edition->zones[0]->id_zone;
                $categoryType = $edition->categoryTypes[0]->id_category_type;
                $category = $edition->categories[0]->id_category;

                $city_tournaments[$t->city][] = array('link'=>'/tournament/'.$edition->tournaments[0]->id.'/zone/'.$t->id_zone.'/category-type/'.$categoryType.'/category/'.$category.'/group/0/show', 'name'=>'', 'edition_name' => $t->edition_name, 'slug' => $t->slug);

                /*
                if($tournament->id_tournament_type == 1){

                    $city_tournaments[$t->city][] = array('link'=>'/tournament/'.$t->id_tournament.'/zone/'.$zone.'/category-type/'.$categoryType.'/category/'.$category.'/group/0/show', 'name'=>$t->tournament_name, 'edition_name' => $t->edition_name);

                }elseif($tournament->id_tournament_type == 2){

                    $city_tournaments[$t->city][] = array('link'=>'/tournament/'.$t->id_tournament.'/zone/'.$zone.'/category-type/'.$categoryType.'/category/'.$category.'/bracket/0/phase/0/show', 'name'=>$t->tournament_name, 'edition_name' => $t->edition_name);

                }
                 *
                 */

            endforeach;

            foreach($t_cities as $t):
                $menu_tournaments[$t->city] = array('link'=>'#', 'name'=>$t->edition_name, 'id_edition' => $t->id_edition,'items'=> $city_tournaments[$t->city] );
            endforeach;


            $news_categories = DB::table('news')
                                ->join('news_categories', 'news.id_news_category', '=', 'news_categories.id')
                                ->select('news_categories.name', 'news_categories.slug', DB::raw('count(*) as tot_news'))
                                ->havingRaw("COUNT(*) > 0")
                                ->groupBy('news_categories.name', 'news_categories.slug')
                                ->get()
                                ->toArray();


            $view->with('menu_tournaments', $menu_tournaments)
                 ->with('menu', $menu)
                 ->with('news_categories', $news_categories)
                 ;

        });

        view()->composer('admin.layouts.menu', function ($view) {

            $tournaments = Tournament::where('date_end', '>=', Carbon::now('Europe/Rome'))
                                        ->where('id_tournament_type', '=', 1)
                                        ->where('generated', '=', 1)
                                        ->with('edition')
                                        ->orderBy('date_start')
                                        ->get();


            $view->with('menu_tournaments', $tournaments)
                 ->with('role', Role::find(User::find(Auth::id())->id_role));

        });

    }
}