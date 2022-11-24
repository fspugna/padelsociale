<?php

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

Route::get('news/{slug}', ['as' => 'news', 'uses' => 'NewsController@show']);  

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
Route::post('logout', [ 'as' => 'logout', 'uses' => 'Auth\LoginController@logout' ]);
  
  // Password Reset Routes...
Route::post('password/email', [ 'as' => 'password.email', 'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail' ]);
Route::get('password/reset', [ 'as' => 'password.request', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm' ]);
Route::post('password/reset', [ 'as' => 'password.update', 'uses' => 'Auth\ResetPasswordController@reset' ]);
Route::get('password/reset/{token}', [ 'as' => 'password.reset', 'uses' => 'Auth\ResetPasswordController@showResetForm' ]);
  
// Registration Routes...
Route::get('register', [ 'as' => 'register', 'uses' => 'Auth\RegisterController@showRegistrationForm' ]);
Route::post('register', [ 'as' => '', 'uses' => 'Auth\RegisterController@register' ]);
  /** END AUTH ROUTES */

Route::group(['middleware' => ['auth']], function () {    


    Route::get('/profile', ['as' => 'profile', 'uses' => 'UserController@profile']);                
    Route::get('/home', ['as'=>'home', 'uses' => 'HomeController@index'] );
    Route::post('/upload_profile_image', ['as'=>'upload_profile_image', 'uses'=>'UserController@upload_profile_image']);
    Route::post('/remove_profile_image', ['as'=>'remove_profile_image', 'uses'=>'UserController@remove_profile_image']);
    
    Route::get('/rounds/{id_group}/index', ['as' => 'rounds.index', 'uses' => 'RoundController@index']);        
    Route::get('groups/{id_group}/classification', ['as' => 'group.classification', 'uses' => 'GroupController@classification']);            
    
    Route::get('bracket/{id_bracket}/show', ['as' => 'show_bracket', 'uses' => 'BracketController@show']);        

    Route::get('rankings/year/{year}', ['as' => 'rankings', 'uses' => 'RankingController@index']);
    Route::get('rankings/live', ['as' => 'rankings', 'uses' => 'RankingController@live']);

    Route::get('player/search', ['as' => 'search', 'uses' => 'UserController@searchPlayer']);                        
    Route::get('player/{id_plager}/get', ['as' => 'search', 'uses' => 'UserController@get']);                


    Route::group(['middleware' => ['player']], function () {                    

        Route::group(['as' => 'teams.'], function () {
            Route::get('teams/index', ['as' => 'index', 'uses' => 'TeamController@index']);
            Route::get('teams/myteams', ['as' => 'myteams', 'uses' => 'TeamController@myTeams']);
            Route::get('teams/{id_team}/show', ['as' => 'show', 'uses' => 'TeamController@show']);
            Route::get('teams/create', ['as' => 'create', 'uses' => 'TeamController@create']);
            Route::post('teams/store', ['as' => 'store', 'uses' => 'TeamController@store']);
            Route::get('teams/{id_team}/edit', ['as' => 'edit', 'uses' => 'TeamController@edit']);
            Route::put('teams/update', ['as' => 'update', 'uses' => 'TeamController@update']);
            Route::delete('teams/{id_team}/delete', ['as' => 'delete', 'uses' => 'TeamController@destroy']);                                
            Route::delete('teams/{id_team}/deleteMyTeam', ['as' => 'deleteMyTeam', 'uses' => 'TeamController@destroyMyTeam']);                                
            Route::post('teams/changePlayer', ['as' => 'changePlayer', 'uses' => 'TeamController@changePlayer']);
        });

        Route::group(['as' => 'player.'], function () {
            Route::group(['as' => 'tournament.'], function () {
                Route::get('tournament/currents', ['as' => 'currents', 'uses' => 'TournamentController@currents']);
            });                                
        });
        

        
        Route::post('matches/schedule', ['as' => 'matches.schedule', 'uses' => 'MatchController@schedule']);            
        Route::post('scores/store', ['as' => 'scores.store', 'uses' => 'ScoreController@store']);                        

        Route::group(['as' => 'team_player.'], function () {
            Route::post('teams/store', ['as' => 'store', 'uses' => 'TeamPlayerController@store']);
        });

        Route::resource('teamPlayers', 'TeamPlayerController');            

        Route::get('tournaments/{id_tournament}/subscription', ['as' => 'subscription', 'uses' => 'TournamentController@subscription']);
        Route::get('tournaments/{id_tournament}/myteam', 'TournamentController@myteam');
        

        Route::post('tournaments/subscribe', ['as' => 'subscribe', 'uses' => 'TournamentController@subscribe']);
        Route::get('/{id_zone}/clubs', ['as' => 'zoneclubs', 'uses' => 'ClubController@clubsByZone']);

        
    });

    Route::group(['middleware' => ['club']], function () {
        Route::resource('clubs', 'ClubController');        
    });

    Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin.'], function() {                            
        
        Route::group(['middleware' => ['admin']], function () {
            Route::resource('users', 'UserController');

            Route::get('images', ['as' => 'images.index', 'uses' => 'ImageController@index']);                                
            Route::get('images/create', ['as' => 'images.create', 'uses' => 'ImageController@create']);                                
            Route::post('images/store', ['as' => 'images.store', 'uses' => 'ImageController@store']);                                

            Route::resource('galleries', 'GalleryController');
            Route::resource('galleryImages', 'GalleryImageController');

            Route::resource('countries', 'CountryController');
            Route::resource('cities', 'CityController');
            Route::resource('zones', 'ZoneController');

            Route::resource('tournamentTypes', 'TournamentTypeController');
            Route::resource('categoryTypes', 'CategoryTypeController');
            
            Route::resource('editions', 'EditionController');    
            
            /** Tournament */
            Route::post('tournaments/store', 'TournamentController@store');
            Route::get('tournaments/{id_tournament}/edit', 'TournamentController@edit');
            Route::post('tournaments/{id_tournament}/update', 'TournamentController@update');
            Route::delete('tournaments/{id_tournament}/delete', 'TournamentController@destroy');
            Route::post('tournaments/generate', 'TournamentController@generate');

            Route::get('tournaments/{id_tournament}/subscriptions', ['as' => 'subscriptions', 'uses' => 'TournamentController@subscriptions']);
            Route::get('tournaments/{id_tournament}/brackets', ['as' => 'brackets', 'uses' => 'TournamentController@brackets']);
            Route::get('phase/{id_phase}/generate', ['as' => 'phase_matches', 'uses' => 'PhaseController@generate']);            

            Route::post('tournaments/assigncategories', ['as' => 'assigncategories', 'uses' => 'TournamentController@assigncategories']);
                        
            Route::get('/division/{id_division}/index', ['as' => 'divisions.index', 'uses' => 'DivisionController@index']);            
            Route::get('/division/{id_division}/edit', ['as' => 'divisions.edit', 'uses' => 'DivisionController@edit']);            

            Route::get('/groups/prepare', ['as' => 'groups.prepare', 'uses' => 'GroupController@prepare']);
            Route::post('/groups/generate', ['as' => 'groups.generate', 'uses' => 'GroupController@generate']);                        
            Route::post('/groups/online', ['as' => 'groups.online', 'uses' => 'GroupController@online']);            

            Route::post('/brackets/teams/add', ['as' => 'brackets.teams.add', 'uses' => 'BracketController@addTeam']);            
            Route::post('/brackets/teams/remove', ['as' => 'brackets.teams.add', 'uses' => 'BracketController@removeTeam']);            
            Route::post('/brackets/{id_bracket}/edit', ['as' => 'brackets.edit', 'uses' => 'BracketController@edit']);
            Route::post('/brackets/online', ['as' => 'brackets.online', 'uses' => 'BracketController@online']);           

            //Route::resource('groups', 'GroupController');        
            Route::resource('categories', 'CategoryController');     
                        

            
            Route::resource('userMetas', 'UserMetaController');        
            
            Route::resource('editionClubs', 'EditionClubController');
            Route::resource('editionTeams', 'EditionTeamController');            
            
            
            Route::resource('categoryTeams', 'CategoryTeamController');
            
            Route::resource('rounds', 'RoundController');
            Route::resource('matches', 'MatchController');
            
            Route::resource('classifications', 'ClassificationController');
            Route::resource('news', 'NewsController');
            Route::resource('pages', 'PageController');
            
            Route::resource('rankings', 'RankingController');
        });
        
    });
});

Route::resource('groupTeams', 'GroupTeamController');