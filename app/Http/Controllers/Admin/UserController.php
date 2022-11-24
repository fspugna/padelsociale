<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\UserRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use Validator;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\User;
use App\Models\UserMeta;
use App\Models\UserMetaItem;
use App\Models\Role;
use App\Models\Club;
use App\Models\UserClub;
use App\Models\Zone;
use App\Models\City;
use App\Models\TeamPlayer;
use App\Models\GroupTeam;
use App\Models\PhaseTeam;
use App\Models\Partner;

use App\Notifications\UserActivated;

class UserController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $input = $request->all();

        if(!$request->has('filter-role') ):
            $users = User::orderBy('surname', 'ASC')
                            ->orderBy('name', 'ASC')
                            ->get();
            $input['q'] = '';
            $input['filter-role'] = '';
            $input['filter-gender'] = '';
            $input['filter-city'] = '';
            $input['filter-club'] = '';
            $input['filter-status'] = '';

            $filtro_attivo = false;
        else:
            $q = $input['q'] ?? '';
            $status = 0;
            if($input['filter-status'] == 'attivo'):
                $status = 1;
            endif;
            $users = User::where(function($query) use ($q){
                                $query->where('users.name', 'LIKE', '%'.$q.'%');
                                $query->orWhere('users.surname', 'LIKE', '%'.$q.'%');
                            });
                        if(isset($input['filter-gender'])):
                            $users = $users->where('gender', '=', $input['filter-gender']);
                        endif;

                        $users->whereHas('role', function ($query) use($input) {
                                $query->where('name', '=', $input['filter-role']);
                            });

                        if(isset($input['filter-city'])):
                            $users = $users->where('id_city', '=', $input['filter-city']);
                        endif;

                        if(isset($input['filter-email'])):
                            $users = $users->where('email', 'LIKE', '%'.$input['filter-email'].'%');
                        endif;

                        if(isset($input['filter-mobile-phone'])):
                            $users = $users->where('mobile_phone', 'LIKE', '%'.$input['filter-mobile-phone'].'%');
                        endif;

                        $users = $users
                                ->where('status', '=', $status)
                                ->orderBy('surname', 'ASC')
                                ->orderBy('name', 'ASC')
                                ->get();


            if( isset($input['filter-club']) && $input['filter-club'] != '' ):
                $user_list = $users;

                $users = new Collection;

                foreach($user_list as $user):
                    if( $user->id_role == 2 && $user->id_club == $input['filter-club']){
                        $users->push( $user );
                    }elseif( $user->id_role == 3 ){
                        $own_club = Club::where('id_user', '=', $user->id)->first();
                        if( $own_club ):
                            if( $own_club->id == $input['filter-club'] ):
                                $users->push( $user );
                            endif;
                        endif;
                    }
                endforeach;
            endif;

            $filtro_attivo = true;
        endif;


        $currentPage = Paginator::resolveCurrentPage() - 1;
        $perPage = 50;
        $currentPageSearchResults = $users->slice($currentPage * $perPage, $perPage)->all();
        $users = new LengthAwarePaginator($currentPageSearchResults, count($users), $perPage);

        $cities = City::orderBy('name')->get()->pluck('name', 'id')->toArray();
        $cities = ['' => 'TUTTE'] + $cities;

        $clubs = [];
        if( isset($input['filter-city'])):
            $clubs_list = Club::where('id_city', '=', $input['filter-city'])->get();

            foreach($clubs_list as $club):
                $clubs[$club->city->country->name . ' - ' . $club->city->name] [ $club->id ] = $club->name . ' - ' . $club->address ;
                asort($clubs[$club->city->country->name . ' - ' . $club->city->name]);
            endforeach;
        endif;



        return view('admin.users.index')
            ->with('users', $users)
            ->with('cities', $cities)
            ->with('clubs', $clubs)
            ->with('q', $input['q'] ?? '')
            ->with('filter_role', $input['filter-role'] ?? '')
            ->with('filter_gender', $input['filter-gender'] ?? '')
            ->with('filter_city', $input['filter-city'] ?? '')
            ->with('filter_club', $input['filter-club'] ?? '')
            ->with('filter_status', $input['filter-status'] ?? '')
            ->with('filter_email', $input['filter-email'] ?? '')
            ->with('filter_mobile_phone', $input['filter-mobile-phone'] ?? '')
            ->with('filtro_attivo', $filtro_attivo)
            ;
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        $input = $request->all();

        $user = $this->userRepository->create($input);

        Flash::success('User saved successfully.');

        return redirect(route('admin.users.index'));
    }

    /**
     * Display the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('admin.users.index'));
        }

        return view('admin.users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->find($id);

        $roles = Role::where('id', '!=', 4)->pluck('name', 'id');

        foreach($roles as $k => $role):
            $roles[$k] = trans('labels.'.$role);
        endforeach;

        $stati = [0 =>  trans('labels.disabled'), 1 => trans('labels.active') ];

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('admin.users.index'));
        }

        $cities = City::get()->pluck('name', 'id');

        $clubs_list = Club::get();
        $clubs = [];

        foreach($clubs_list as $club):
            if( $club->city ):
                $clubs[$club->city->country->name . ' - ' . $club->city->name] [ $club->id ] = $club->name . ' - ' . $club->address ;
            endif;
        endforeach;

        if( $user->id_role == 3 ):
            $user_club = Club::where('id_user', '=', $id)->first();
        else:
            $user_club = null;
        endif;

        return view('admin.users.edit')
                    ->with('user', $user)
                    ->with('roles', $roles)
                    ->with('stati', $stati)
                    ->with('cities', $cities)
                    ->with('clubs', $clubs)
                    ->with('user_club', $user_club)
        ;
    }

    /**
     * Update the specified User in storage.
     *
     * @param int $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        $input = $request->all();

        $user = $this->userRepository->find($id);

        if( isset($input['new_password']) && !empty($input['new_password'])):

            if( isset($input['confirm_password']) ):
                if( $input['new_password'] == $input['confirm_password'] ):

                    $user->password = Hash::make($input['new_password']);
                    $user->save();

                else:
                    Flash::error('La password di conferma non corrisponde');
                    return redirect(route('admin.users.index'));
                endif;
            else:
                Flash::error('Inserire una passowrd di conferma');
                return redirect(route('admin.users.index'));
            endif;
        endif;


        $old_status = $user->id_status;
        $new_status = $input['status'];

        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('admin.users.index'));
        }

        $user = $this->userRepository->update($input, $id);

        if( $user->id_role == 3 ){
            $club = Club::where('id_user', '=', $user->id)->first();
            if( empty($club) ){
                $club = new Club;
                $club->id_user = $user->id;
            }
            $club->name = $input['club_name'];
            $club->id_city = $input['id_city'];
            $club->address = $input['club_address'];
            $club->phone = $input['club_phone'];
            $club->mobile_phone = $input['club_mobile_phone'];
            $club->description = $input['club_description'];
            $club->save();
        }

        if($old_status == 0 && $new_status == 1){
            $user->notify(new UserActivated($user));
        }

        Flash::success('User updated successfully.');

        return redirect(route('admin.users.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('admin.users.index'));
        }

        $teams = TeamPlayer::where('id_player', '=', $id)->get();
        foreach($teams as $team){
            $groups = GroupTeam::where('id_team', '=', $team->id_team)->get();
            //dd($groups, $id_team, $id);
            if( count($groups) > 0 ){
                foreach($groups as $group):
                    //dd($group->group->id, $team->id, $group->group->division->id);
                    Flash::error('Non puoi cancellare ' . $user->name . ' ' . $user->surname . ' perché è inserito almeno nel girone ' . $group->group->name . " della zona " . $group->group->division->zone->name . " categoria " . $group->group->division->category->name . " del torneo " . $group->group->division->tournament->edition->edition_name);
                    return redirect(route('admin.users.index'));
                endforeach;
            }

            $phases = PhaseTeam::where('id_team', '=', $team->id)->get();
            if( count($phases) > 0 ){
                Flash::error('Non puoi cancellare ' . $user->name . ' ' . $user->surname . ' perché è inserito in almeno un tabellone');
                return redirect(route('admin.users.index'));
            }
        }

        $this->userRepository->delete($id);

        Flash::success('User deleted successfully.');

        return redirect(route('admin.users.index'));
    }


    public function searchPlayer(Request $request){
        if($request->has('q')){

            $search = $request->input('q');

            $users = User::where('id_role', '=', 2)
                            ->where(function($query) use ($search){
                                $query->where('name', 'like', '%'.$search.'%');
                                $query->orWhere('surname', 'like', '%'.$search.'%');
                                $query->orWhere(DB::raw("CONCAT(`name`, ' ', `surname`)"), 'like', '%'.$search.'%');
                            })
                            ->where('status', '=', '1')
                            ->get();

            return $users;
        }else{
            return response('ERROR: q param missing', 500);
        }
    }

    public function profile(){
        $user = User::where('id', '=', Auth::id())->first();

        $user_avatar = '';
        foreach($user->metas as $k => $meta){
            if($meta->meta == 'avatar'){
                $user_avatar = $meta->meta_value;
                unset($user->metas[$k]);
            }
        }


        $userMetaItems = UserMetaItem::where('meta_cat', '=', 'info')->get();

        if($user->id_role == 1):

            return view('admin.users.profile')
                ->with('user', $user)
                ->with('user_avatar', $user_avatar)
                ->with('userMetaItems', $userMetaItems)
                ->with('sliders', null)
                ->with('clubs', null)
                ->with('zones', null)
                ->with('club', null)
                ;

        elseif($user->id_role == 2):

            $sliders = UserMetaItem::where('meta_type', '=', 'slider')->get();

            $clubs = UserClub::where('id_user', '=', $user->id)->get();

            return view('admin.users.profile')
                ->with('user', $user)
                ->with('user_avatar', $user_avatar)
                ->with('userMetaItems', $userMetaItems)
                ->with('sliders', $sliders)
                ->with('clubs', $clubs)
                ->with('zones', null)
                ->with('club', null)
                ;

        elseif($user->id_role == 3):

            $zones = Zone::all();

            $club = Club::where('id_user', '=', $user->id)->first();

            //dd($user, $avatar, $userMetaItems, $zones);

            return view('admin.users.profile')
                ->with('user', $user)
                ->with('user_avatar', $user_avatar)
                ->with('userMetaItems', $userMetaItems)
                ->with('sliders', null)
                ->with('clubs', null)
                ->with('zones', $zones)
                ->with('club', $club)
                ;

        elseif($user->id_role == 4):

            $zones = Zone::all();

            $partner = Partner::where('id_user', '=', $user->id)->first();

            //dd($user, $avatar, $userMetaItems, $zones);

            return view('admin.users.profile')
                ->with('user', $user)
                ->with('user_avatar', $user_avatar)
                ->with('userMetaItems', $userMetaItems)
                ->with('sliders', null)
                ->with('clubs', null)
                ->with('zones', $zones)
                ->with('partner', $partner)
                ;

        endif;
    }

    public function upload_profile_image(Request $request){

        if($request->hasFile('profile_image')):

            $image_rule = [
                'profile_image' => 'image|mimes:jpg,jpeg,png|max:10240',
            ];

            $image_messages = [
                'profile_image.image' => "Il file caricato non è un'immagine",
                'profile_image.mimes' => "L'immagine deve essere di tipo .jpg o .png",
                'profile_image.max' => "ATTENZIONE: questo file presenta dei problemi. L'immagine supera i 10mb."
            ];

            $image_validator = Validator::make(['profile_image' => $input['profile_image']], $image_rule, $image_messages);
            if($image_validator->fails()){
                return back()->withErrors($image_validator);
            }

            $file = $request->file('profile_image');

            //Display File Extension
            //$ext = $file->getClientOriginalExtension();

            //Display File Real Path
            //$file->getRealPath();

            //Display File Size
            //$file->getSize();

            //Display File Mime Type
            //$file->getMimeType();

            //Move Uploaded File
            $filename = Storage::disk('public')->putFile('avatars', $file);

            $userMeta = UserMeta::where('id_user', '=', Auth::id())
                                    ->where('meta', '=', 'avatar')
                                    ->first();
            if(!$userMeta){
                $userMeta = new UserMeta;
                $userMeta->id_user = Auth::id();
                $userMeta->meta = 'avatar';
            }

            Storage::disk('public')->delete($userMeta->meta_value);

            $userMeta->meta_value = $filename;
            $userMeta->save();
        endif;

        return back()->withInput();
    }

    public function remove_profile_image(Request $request){

        $userMeta = UserMeta::where('id_user', '=', Auth::id())
                                ->where('meta', '=', 'avatar')
                                ->first();

        if($userMeta):
            Storage::disk('public')->delete($userMeta->meta_value);
            $userMeta->delete();
        endif;

        return back()->withInput();
    }

    public function get($id_player){
        return response()->json( User::where('id', '=', $id_player)->with('metas')->first());
    }


    public function updateProfile(Request $request){

        $input = $request->all();

        //dd($input);

        $user = User::where('id', '=', Auth::id())->first();

        $user->name = $input['name'];
        $user->surname = $input['surname'];
        $user->email = $input['email'];
        $user->mobile_phone = $input['mobile_phone'];
        $user->save();

        if( isset($input['new_password']) && !empty($input['new_password']) ){
            if( empty($input['confirm_new_password']) ){
                return back()->withInput()->withErrors('Devi confermare la nuova password');
            }elseif( $input['confirm_new_password'] != $input['new_password']){
                return back()->withInput()->withErrors('La password di conferma non corrisponde');
            }else{
                $user->password = Hash::make($input['new_password']);
                $user->save();
            }
        }

        return redirect(route('admin.home'));
    }

}
