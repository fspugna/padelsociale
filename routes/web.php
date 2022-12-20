<?php
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@welcome')->name('welcome');
Route::get('artigiano/{command}/{param}', ['as' => 'cache', 'uses' => 'CacheController@show']);

Route::get('/setadminpwd', 'Auth\ResetPasswordController@setAdminPwd');

/*
Route::get('/', function () {
    return View::make('front-page');
});
*/

//Auth::routes();
/** AUTH ROUTES
 */

 // Authentication Routes...
Route::get('login',  [ 'as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm' ]);
Route::post('login', [ 'as' => '', 'uses' => 'Auth\LoginController@login' ]);
Route::any('logout', [ 'as' => 'logout', 'uses' => 'Auth\LoginController@logout' ]);

  // Password Reset Routes...
Route::post('password/email', [ 'as' => 'password.email', 'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail' ]);
Route::get('password/reset', [ 'as' => 'password.request', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm' ]);
Route::post('password/reset', [ 'as' => 'password.update', 'uses' => 'Auth\ResetPasswordController@reset' ]);
Route::get('password/reset/{token}', [ 'as' => 'password.reset', 'uses' => 'Auth\ResetPasswordController@showResetForm' ]);

// Registration Routes...
Route::get('register', [ 'as' => 'register', 'uses' => 'Auth\RegisterController@showRegistrationForm' ]);
Route::post('register', [ 'as' => '', 'uses' => 'Auth\RegisterController@register' ]);
Route::get('register-club', [ 'as' => 'register-club', 'uses' => 'Auth\RegisterController@showRegistrationClubForm' ]);
Route::post('register-club', [ 'as' => 'register-club', 'uses' => 'Auth\RegisterController@registerClub' ]);
  /** END AUTH ROUTES */

Route::group(['middleware' => ['auth']], function () {

  Route::get('edit-profile', ['as' => 'edit-profile', 'uses' => 'UserController@editProfile']);
  Route::post('/update_profile', ['as'=>'update_profile', 'uses'=>'UserController@updateProfile']);
  Route::post('/gallery/image/{id_image}/delete', ['as'=>'gallery.image.delete', 'uses'=>'UserController@deleteImage']);

});

Route::group(['middleware' => ['auth']], function () {


    Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin.'], function() {

        Route::get('/profile', ['as' => 'profile', 'uses' => 'UserController@profile']);
        Route::post('/update_profile', ['as'=>'update_profile', 'uses'=>'UserController@updateProfile']);


        Route::get('/home', ['as'=>'home', 'uses' => 'HomeController@index'] );
        Route::post('/upload_profile_image', ['as'=>'upload_profile_image', 'uses'=>'UserController@upload_profile_image']);
        Route::post('/remove_profile_image', ['as'=>'remove_profile_image', 'uses'=>'UserController@remove_profile_image']);

        Route::get('/rounds/{id_group}/index', ['as' => 'rounds.index', 'uses' => 'RoundController@index']);
        Route::get('groups/{id_group}/classification', ['as' => 'group.classification', 'uses' => 'GroupController@classification']);

        Route::post('groups/{id_group}/calendar/make', ['as' => 'make_group_calendar', 'uses' => 'GroupController@makeGroupCalendar']);

        Route::get('bracket/{id_bracket}/show', ['as' => 'show_bracket', 'uses' => 'BracketController@show']);
        Route::get('bracket/{id_bracket}/show_macro', ['as' => 'show_macro_bracket', 'uses' => 'BracketController@showMacro']);

        Route::get('rankings/{gender}/year/{year}', ['as' => 'rankings_year', 'uses' => 'RankingController@index']);
        Route::get('rankings/{gender}/live', ['as' => 'rankings_live', 'uses' => 'RankingController@live']);

        Route::get('player/search', ['as' => 'search', 'uses' => 'UserController@searchPlayer']);
        Route::get('player/{id_player}/get', ['as' => 'search', 'uses' => 'UserController@get']);

        Route::get('club/search', ['as' => 'search_club', 'uses' => 'ClubController@search']);
        Route::get('club/{id_club}/get', ['as' => 'search', 'uses' => 'ClubController@get']);

        Route::post('/matches/schedule', ['as' => 'matches.schedule', 'uses' => 'MatchController@schedule']);
        Route::post('/matches/del_schedule', ['as' => 'matches.del_schedule', 'uses' => 'MatchController@delSchedule']);

        Route::post('/macro_matches/schedule', ['as' => 'macro_matches.schedule', 'uses' => 'MacroMatchController@schedule']);
        Route::post('/macro_matches/del_schedule', ['as' => 'macro_matches.del_schedule', 'uses' => 'MacroMatchController@delSchedule']);
        Route::get('/macro_teams/{id_macro_team}/show', ['as' => 'show_macro_team', 'uses' => 'MacroTeamController@edit']);
        Route::post('/macro_teams/update', ['as' => 'macro_team.update', 'uses' => 'MacroTeamController@update']);

        Route::post('/scores/store', ['as' => 'scores.store', 'uses' => 'ScoreController@store']);

        Route::get('images', ['as' => 'images.index', 'uses' => 'ImageController@index']);
        Route::get('images/create', ['as' => 'images.create', 'uses' => 'ImageController@create']);
        Route::post('images/store', ['as' => 'images.store', 'uses' => 'ImageController@store']);
        Route::post('images/destroy', ['as' => 'images.destroy', 'uses' => 'ImageController@destroy']);

        Route::get('images/match/{id_match}', ['as' => 'images.match.index', 'uses' => 'ImageController@imgMatchList']);
        Route::get('images/match/{id_match}/create', ['as' => 'images.match.create', 'uses' => 'ImageController@imgMatchCreate']);
        Route::get('images/match/{id_match}/delete', ['as' => 'images.match.delete', 'uses' => 'ImageController@imgMatchDelete']);


        Route::group(['middleware' => ['admin']], function () {
            Route::resource('users', 'UserController');

            Route::resource('countries', 'CountryController');
            Route::resource('cities', 'CityController');
            Route::resource('zones', 'ZoneController');

            Route::resource('tournamentTypes', 'TournamentTypeController');
            Route::resource('categoryTypes', 'CategoryTypeController');

            Route::resource('editions', 'EditionController');

            Route::resource('events', 'EventController');
            /*
            Route::get('editions', ['as' => 'editions.index', 'uses' => 'EditionController@index']);
            Route::get('editions/create', ['as' => 'editions.create', 'uses' => 'EditionController@create']);
            Route::post('editions/store', ['as' => 'editions.store', 'uses' => 'EditionController@store']);
            Route::get('editions/{id_edition}', ['as' => 'editions.show', 'uses' => 'EditionController@show']);
            Route::get('editions/{id_edition}/edit', ['as' => 'editions.edit', 'uses' => 'EditionController@edit']);
            Route::post('editions/update', ['as' => 'editions.update', 'uses' => 'EditionController@update']);
            Route::delete('editions/{id_edition}', ['as' => 'editions.delete', 'uses' => 'EditionController@destroy']);
            */

            Route::delete('subscription/{id_subscription}/remove', 'SubscriptionController@destroy');
            Route::delete('macro_subscription/{id_subscription}/remove', 'MacroSubscriptionController@destroy');

            /** Tournament */
            Route::post('tournaments/store', 'TournamentController@store');
            Route::get('tournaments/{id_tournament}/edit', 'TournamentController@edit');
            Route::post('tournaments/{id_tournament}/update', 'TournamentController@update');
            Route::delete('tournaments/{id_tournament}/delete', 'TournamentController@destroy');
            Route::post('tournaments/generate', 'TournamentController@generate');
            Route::post('tournaments/subscribe', ['as' => 'subscribe_team', 'uses' => 'TournamentController@subscribe']);
            Route::get('tournaments/{id_tournament}/subscriptions', ['as' => 'subscriptions', 'uses' => 'TournamentController@subscriptions']);
            Route::get('tournaments/{id_tournament}/brackets', ['as' => 'brackets', 'uses' => 'TournamentController@brackets']);

            Route::get('bracket/{id_bracket}/prepare', ['as' => 'bracket_prepare', 'uses' => 'BracketController@prepare']);
            Route::post('bracket/generate', ['as' => 'bracket_prepare', 'uses' => 'BracketController@generate']);
            Route::post('bracket/{id_bracket}/delete', ['as' => 'bracket_delete', 'uses' => 'BracketController@destroy']);
            Route::post('bracket/create', ['as' => 'bracket_create', 'uses' => 'BracketController@create']);

            Route::get('phase/{id_phase}/generate', ['as' => 'phase_matches', 'uses' => 'PhaseController@generate']);

            Route::post('tournaments/assigncategories', ['as' => 'assigncategories', 'uses' => 'TournamentController@assigncategories']);

            Route::get('/division/{id_division}/index', ['as' => 'divisions.index', 'uses' => 'DivisionController@index']);
            Route::get('/division/{id_division}/edit', ['as' => 'divisions.edit', 'uses' => 'DivisionController@edit']);
            Route::delete('/division/{id_division}/remove', ['as' => 'divisions.remove', 'uses' => 'DivisionController@destroy']);
            Route::post('/division/create', ['as' => 'divisions.create', 'uses' => 'DivisionController@create']);

            Route::post('/groups/remove', ['as' => 'groups.remove', 'uses' => 'GroupController@remove']);
            Route::get('/groups/prepare', ['as' => 'groups.prepare', 'uses' => 'GroupController@prepare']);
            Route::post('/groups/generate', ['as' => 'groups.generate', 'uses' => 'GroupController@generate']);
            Route::post('/groups/online', ['as' => 'groups.online', 'uses' => 'GroupController@online']);
            Route::get('/groups/{id_group}/editRounds', ['as' => 'groups.edit_rounds', 'uses' => 'GroupController@showEditRounds']);
            Route::get('/groups/{id_group}/addRound', ['as' => 'groups.add_round', 'uses' => 'GroupController@addRound']);
            Route::post('/groups/updateRounds', ['as' => 'groups.update_rounds', 'uses' => 'GroupController@updateRounds']);
            Route::post('/groups/teams/add', ['as' => 'groups.teams.add', 'uses' => 'GroupController@addTeam']);
            Route::post('/groups/teams/remove', ['as' => 'groups.teams.remove', 'uses' => 'GroupController@removeTeam']);
            Route::post('/groups/{id_group}/macro_calendar/save', ['as' => 'macro_calendar.save', 'uses' => 'RoundController@saveMacroCalendar']);

            Route::get('/rounds/{id_round}/macro_matches/add', ['as' => 'add_round_match', 'uses' => 'RoundController@addMacroMatch']);
            Route::get('/rounds/{id_round}/delete', ['as' => 'delete_round_match', 'uses' => 'RoundController@delete']);
            Route::get('/round/{id_round}/matches', ['as' => 'round_matches', 'uses' => 'RoundController@matches']);

            Route::post('/macro_match/{id_macro_match}/delete', ['as' => 'del_macro_match' , 'uses' => 'MacroMatchController@deleteMacroMatch']);
            Route::post('/macro_match/{id_macro_match}/makeNull', ['as' => 'match_null' , 'uses' => 'MacroMatchController@makeNull']);

            Route::post('/macro_match/{id_macro_match}/sub_match/add', ['as' => 'add_sub_match' , 'uses' => 'MacroMatchController@addSubMatch']);

            Route::get('/matches/{id_match}/delete', ['as' => 'del_match' , 'uses' => 'MatchController@delete']);
            Route::post('/matches/{id_match}/deleteAjax', ['as' => 'del_match' , 'uses' => 'MatchController@deleteAjax']);

            Route::post('/brackets/teams/add', ['as' => 'brackets.teams.add', 'uses' => 'BracketController@addTeam']);
            Route::post('/brackets/teams/remove', ['as' => 'brackets.teams.remove', 'uses' => 'BracketController@removeTeam']);
            Route::post('/brackets/{id_bracket}/edit', ['as' => 'brackets.edit', 'uses' => 'BracketController@edit']);
            Route::post('/brackets/online', ['as' => 'brackets.online', 'uses' => 'BracketController@online']);

            Route::get('/score/{id_match}', ['as' => 'score_match', 'uses' => 'ScoreController@getScore']);

            //Route::resource('groups', 'GroupController');
            Route::resource('categories', 'CategoryController');

            /** Vittoria a tavolino */
            Route::post('/score/{id_match}/forfait', ['as' => 'match.forfait', 'uses' => 'ScoreController@forfait']);

            /* Vittori a tavolino squadra macro */
            Route::post('/score/{id_macro_match}/forfait_macro', ['as' => 'match.forfait_macro', 'uses' => 'ScoreController@forfaitMacro']);
            Route::post('/score/{id_macro_match}/remove_forfait_macro', ['as' => 'match.remove_forfait_macro', 'uses' => 'ScoreController@delForfaitMacro']);
            Route::post('/score/macro/{id_macro_match}/store', ['as' => 'macro_match.all_scores', 'uses' => 'ScoreController@storeAll']);

            Route::resource('userMetas', 'UserMetaController');

            Route::resource('editionClubs', 'EditionClubController');
            Route::resource('editionTeams', 'EditionTeamController');


            Route::resource('categoryTeams', 'CategoryTeamController');

            Route::resource('rounds', 'RoundController');
            Route::resource('matches', 'MatchController');

            Route::resource('classifications', 'ClassificationController');
            Route::resource('news', 'NewsController');
            Route::resource('newsCategories', 'NewsCategoryController');
            Route::resource('pages', 'PageController');
            Route::resource('infos', 'InfoController');


            /* Partners */
            Route::get('/partners', ['as'=>'partners.index', 'uses'=>'PartnerController@index']);
            Route::get('/partners/create', ['as'=>'partners.create', 'uses'=>'PartnerController@create']);
            Route::get('/partners/{id_partner}/edit', ['as'=>'partners.edit', 'uses'=>'PartnerController@edit']);
            Route::post('/partners/store', ['as'=>'partners.store', 'uses'=>'PartnerController@store']);
            Route::post('/partners/update', ['as'=>'partners.update', 'uses'=>'PartnerController@update']);
            Route::post('/partners/actions', ['as'=>'partners.actions', 'uses'=>'PartnerController@actions']);

            /** Gestione banner */
            Route::get('/banners', ['as'=>'banners.index', 'uses'=>'BannerController@banners']);
            Route::get('/banners/create', ['as'=>'banners.create', 'uses'=>'BannerController@create']);
            Route::post('/banners/store', ['as'=>'banners.store', 'uses'=>'BannerController@store']);
            Route::get('/banners/{id_banner}/edit', ['as'=>'banners.edit', 'uses'=>'BannerController@edit']);
            Route::post('/banners/update', ['as'=>'banners.update', 'uses'=>'BannerController@update']);
            Route::post('/banners/delete', ['as'=>'banners.delete', 'uses'=>'BannerController@delete']);
            Route::get('/banners/positions', ['as'=>'banners.positions', 'uses'=>'BannerController@bannerPositions']);
            Route::get('/banners/positions/create', ['as'=>'banners.positions.create', 'uses'=>'BannerController@addBannerPosition']);
            Route::post('/banners/positions/store', ['as'=>'banners.positions.store', 'uses'=>'BannerController@storeBannerPosition']);
            Route::post('/banners/positions/delete', ['as'=>'banners.positions.delete', 'uses'=>'BannerController@deleteBannerPosition']);
            Route::get('/banners/positionings', ['as'=>'banners.positionings', 'uses'=>'BannerController@bannerPositionings']);
            Route::post('/banners/position/{id_position}/positionings', ['as'=>'banners.position.positionings', 'uses'=>'BannerController@getPositionings']);
            Route::post('/banners/positioning/add', ['as'=>'banners.positioning.add', 'uses'=>'BannerController@bannerAddPositioning']);
            Route::post('/banners/positioning/remove', ['as'=>'banners.positioning.remove', 'uses'=>'BannerController@bannerDelPositioning']);

            /** Ranking */
            Route::get('/rankings/assign', ['as'=>'rankings.assign', 'uses'=>'RankingController@assign']);
            Route::get('/rankings/{gender}', ['as'=>'rankings.index', 'uses'=>'RankingController@index']);
            Route::post('/rankings/store', ['as'=>'rankings.store', 'uses'=>'RankingController@store']);
            Route::post('/rankings/store_macro', ['as'=>'rankings.store_macro', 'uses'=>'RankingController@storeMacro']);

            /** Team */
            Route::group(['as' => 'teams.'], function () {
                Route::get('teams/index', ['as' => 'index', 'uses' => 'TeamController@index']);
                Route::get('teams/myteams', ['as' => 'myteams', 'uses' => 'TeamController@myTeams']);
                Route::get('teams/{id_team}/show', ['as' => 'show', 'uses' => 'TeamController@show']);
                Route::get('teams/create', ['as' => 'create', 'uses' => 'TeamController@create']);
                Route::post('teams/store', ['as' => 'store', 'uses' => 'TeamController@store']);
                Route::get('teams/{id_team}/edit', ['as' => 'edit', 'uses' => 'TeamController@edit']);
                Route::put('teams/{id_team}/update', ['as' => 'update', 'uses' => 'TeamController@update']);
                Route::delete('teams/{id_team}/delete', ['as' => 'delete', 'uses' => 'TeamController@destroy']);
                Route::delete('teams/{id_team}/deleteMyTeam', ['as' => 'deleteMyTeam', 'uses' => 'TeamController@destroyMyTeam']);
            });

            Route::group(['as' => 'player.'], function () {
                Route::group(['as' => 'tournament.'], function () {
                    Route::get('tournament/currents', ['as' => 'currents', 'uses' => 'TournamentController@currents']);
                });
            });


            Route::group(['as' => 'team_player.'], function () {
                Route::post('teams/store', ['as' => 'store', 'uses' => 'TeamPlayerController@store']);
            });

            Route::resource('teamPlayers', 'TeamPlayerController');

            Route::get('tournaments/{id_tournament}/subscription', ['as' => 'subscription', 'uses' => 'TournamentController@subscription']);
            Route::get('tournaments/{id_tournament}/myteam', 'TournamentController@myteam');
            Route::post('tournaments/subscribe_my_team', ['as' => 'subscribe_team', 'uses' => 'TournamentController@subscribe_my_team']);


            Route::get('/{id_zone}/clubs', ['as' => 'zoneclubs', 'uses' => 'ClubController@clubsByZone']);


        });


        /*
        Route::group(['middleware' => ['club']], function () {
            Route::resource('clubs', 'ClubController');
        });
        */
    });


    //Route::get('edition/{id_edition}/images', ['as'=>'edition.images', 'uses' => 'EditionController@images'] );
    Route::get('team/{id_team}/show', ['as'=>'show_team', 'uses' => 'TeamController@show'] );
    Route::post('tournament/categories', ['as'=>'zone.categories', 'uses' => 'TournamentController@categories'] );
    Route::get('division/{id_division}/show', ['as'=>'division.show', 'uses' => 'DivisionController@show'] );
    Route::get('bracket/{id_bracket}/show', ['as'=>'bracket.show', 'uses' => 'BracketController@show'] );
    Route::post('subscriptions/list', ['as'=>'subscriptions', 'uses' => 'SubscriptionController@list'] );


    Route::get('old-events', ['as' => 'old-events', 'uses' => 'CalendarController@old']);
    Route::get('old-events/{slug}', ['as' => 'old-events-by-city', 'uses' => 'CalendarController@oldByCity']);

    Route::get('teams/{id_team}/show', ['as'=>'show-team', 'uses' => 'TeamController@show']);
    Route::get('macro_teams/{id_macro_team}/show', ['as'=>'show-macro-team', 'uses' => 'MacroTeamController@show']);

    Route::post('/teams/changePlayer', ['as' => 'changePlayer', 'uses' => 'TeamController@changePlayer']);

});

Route::get('edition/{id_edition}/rules', ['as'=>'edition.rules', 'uses' => 'EditionController@rules']);
Route::get('edition/{id_edition}/zone_rules', ['as'=>'edition.rules', 'uses' => 'EditionController@zone_rules']);
Route::get('edition/{id_edition}/awards', ['as'=>'edition.rules', 'uses' => 'EditionController@awards']);
Route::get('edition/{id_edition}/zones_and_clubs', ['as'=>'zone_and_clubs', 'uses' => 'EditionController@zonesAndClubs']);

/************* FRONTEND ROUTES *****************/

Route::get('pages/{slug}', ['as' => 'page', 'uses' => 'PageController@show']);
Route::get('news', ['as' => 'news', 'uses' => 'NewsController@showAll']);
Route::get('news/category/{slug}', ['as' => 'news-slug', 'uses' => 'NewsController@showCategory']);
Route::get('news/{slug}', ['as' => 'news-slug', 'uses' => 'NewsController@showSingle']);
Route::get('news-archive', ['as' => 'news-arichive', 'uses' => 'NewsController@archive']);


Route::get('clubs', ['as'=>'clubs.index', 'uses' => 'ClubController@index']);
Route::get('clubs/{id_club}/show', ['as'=>'clubs.show', 'uses' => 'ClubController@show']);
Route::get('clubs/city/{slug_city}', ['as'=>'clubs.city.show', 'uses' => 'ClubController@showByCity']);

Route::get('partners/', ['as'=>'partners.index', 'uses' => 'PartnerController@index']);
Route::get('partners/city/{slug_city}', ['as'=>'partners.city.show', 'uses' => 'PartnerController@showByCity']);
Route::get('partner/{id_partner}/show', ['as'=>'partner.show', 'uses' => 'PartnerController@show']);

//Route::get('group/{id_group}/show', ['as'=>'show_group', 'uses' => 'GroupController@show'] );
Route::get('subscription', ['as'=>'subscription', 'uses' => 'SubscriptionController@subscription'] );
Route::get('rankings/live', ['as' => 'rankings', 'uses' => 'RankingController@live']);
Route::group(['middleware' => ['auth', 'player']], function () {
    Route::post('subscribe', ['as'=>'subscribe', 'uses' => 'SubscriptionController@subscribe'] );
});

Route::get('rankings/{gender}/city/{id_city}', ['as' => 'rankings', 'uses' => 'RankingController@indexByCity']);
Route::get('rankings/{gender}', ['as' => 'rankings', 'uses' => 'RankingController@index']);

Route::get('calendar/event/{id_event}', ['as' => 'calendar', 'uses' => 'CalendarController@calendar']);
Route::get('calendar/event/{id_event}/city/{slug}', ['as' => 'calendar-by-city', 'uses' => 'CalendarController@calendarByCity']);
Route::get('calendar/event/{id_event}/city/{slug}/club/{id_club}', ['as' => 'calendar-by-city', 'uses' => 'CalendarController@calendarByClub']);

Route::get('current-events', ['as' => 'current-events', 'uses' => 'CalendarController@currents']);
Route::get('current-events/{slug}', ['as' => 'current-events-by-city', 'uses' => 'CalendarController@currentsByCity']);

Route::get('next-events', ['as' => 'next-events', 'uses' => 'CalendarController@next']);
Route::get('next-events/{slug}', ['as' => 'next-events-by-city', 'uses' => 'CalendarController@nextByCity']);

Route::get('tournament/{id_tournament}/show', ['as'=>'show_tournament', 'uses' => 'TournamentController@show'] );
Route::get('tournament/{id_tournament}/zone/{id_zona}/show', ['as'=>'show_tournament', 'uses' => 'TournamentController@showZone'] );
Route::get('tournament/{id_tournament}/zone/{id_zona}/category-type/{id_category_type}/show', ['as'=>'show_tournament', 'uses' => 'TournamentController@showCategoryType'] );
Route::get('tournament/{id_tournament}/zone/{id_zona}/category-type/{id_category_type}/category/{id_category}/show', ['as'=>'show_tournament', 'uses' => 'TournamentController@showCategory'] );
Route::get('tournament/{id_tournament}/zone/{id_zona}/category-type/{id_category_type}/category/{id_category}/group/{id_group}/show', ['as'=>'group_show', 'uses' => 'TournamentController@showGroup'] );
Route::get('tournament/{id_tournament}/zone/{id_zona}/category-type/{id_category_type}/category/{id_category}/group/{id_group}/round/{id_round}/show', ['as'=>'round_show', 'uses' => 'TournamentController@showRound'] );
Route::get('tournament/{id_tournament}/zone/{id_zona}/category-type/{id_category_type}/category/{id_category}/bracket/{id_bracket}/showFull', ['as'=>'bracket_show_full', 'uses' => 'BracketController@showFull']);
Route::get('tournament/{id_tournament}/zone/{id_zona}/category-type/{id_category_type}/category/{id_category}/bracket/{id_bracket}/phase/{id_phase}/show', ['as'=>'bracket_show', 'uses' => 'TournamentController@showBracket'] );
Route::get('tournament/{id_tournament}/zone/{id_zona}/category-type/{id_category_type}/subscribed', ['as'=>'show_tournament', 'uses' => 'TournamentController@showSubscribed'] );

Route::get('players/{gender}', ['as'=>'players.index', 'uses' => 'PlayerController@index']);
Route::get('players/{gender}/city/{slug_city}', ['as'=>'players.index', 'uses' => 'PlayerController@showByCity']);
Route::get('players/{id_player}/show', ['as'=>'players.show', 'uses' => 'PlayerController@show']);

Route::get('match-archive/event/{id_event}', ['as' => 'event_archive', 'uses' => 'CalendarController@matchArchive']);
Route::get('match-archive/event/{id_event}/city/{slug}', ['as' => 'calendar-by-city', 'uses' => 'CalendarController@matchArchiveByCity']);
Route::get('match-archive/event/{id_event}/city/{slug}/club/{id_club}', ['as' => 'calendar-by-city', 'uses' => 'CalendarController@matchArchiveByClub']);

Route::get('archive/matches/', ['as' => 'match_archive', 'uses' => 'CalendarController@archiveMatches']);
Route::get('archive/matches/{year}', ['as' => 'match-archive-year', 'uses' => 'CalendarController@archiveMatchesByYear']);

/*********** END FRONTEND ROUTES ************* */
