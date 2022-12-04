<?php

namespace App\Http\Controllers\Auth;

use Session;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\UserMeta;
use App\Models\Zone;
use App\Models\Club;
use App\Models\City;

use App\Notifications\NewUser;

use Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = 'admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm(){
        $cities = City::orderBy('name')->get();
        return view('register')->with('cities', $cities);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'mobile_phone' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function register(Request $request)
    {
        $data = $request->all();

        if($data['password'] != $data['confirm_password']):
            return back()->withInput()->withErrors('La password di conferma non corrisponde');
        endif;

        if( empty($data['email']) && empty($data['mobile_phone']) ):
            return back()->withInput()->withErrors('Inserire almeno l\'email o il telefono');
        endif;

        if( !empty($data['email']) ):
            $check_email = User::where('id_role', '=', 2)
                                ->where('email', '=', $data['email'])
                                ->where('id_role', $data['id_role'])
                                ->first();
            if($check_email):
                return back()->withInput()->withErrors('L\'email inserita è già utilizzata');
            endif;
        endif;

        if( !empty($data['mobile_phone']) ):
            $check_mobile_phone = User::where('mobile_phone', '=', $data['mobile_phone'])
                                        ->where('id_role', $data['id_role'])
                                        ->first();
            if($check_mobile_phone):
                return back()->withInput()->withErrors('Il cellulare inserito è già utilizzato');
            endif;
        endif;

        $user = User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'mobile_phone' => $data['mobile_phone'],
            'password' => Hash::make($data['password']),
            'id_role' => $data['id_role'],
            'gender' => $data['gender'],
            'id_city' => $data['id_city']
        ]);

        if(isset($data['mobile_phone'])):
            $userMeta = new UserMeta;
            $userMeta->id_user = $user->id;
            $userMeta->meta = 'mobile_phone';
            $userMeta->meta_value = $data['mobile_phone'];
            $userMeta->save();
        endif;

        if($request->hasFile('profile_image')):

            $image_rule = [
                'profile_image' => 'mimes:jpg,jpeg,png|max:10240',
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

            //Move Uploaded File
            $filename = Storage::disk('public')->putFile('avatars', $file);

            $userMeta = new UserMeta;
            $userMeta->id_user = $user->id;
            $userMeta->meta = 'avatar';
            $userMeta->meta_value = $filename;
            $userMeta->save();

        endif;

        /** Notify administrators about the new registration */
        // $users = User::where('id_role', '=', 1)->get();
        // foreach($users as $user_notify):
        //     $user_notify->notify(new NewUser($user));
        // endforeach;

        Session::flash('message', 'L\'utenza è stata creata con successo. Verrai notificato non appena sarai stato abilitato dall\'amministratore');

        return redirect(route('welcome'));
    }

    function showRegistrationClubForm(){
        $zones = Zone::all();
        return view('register-club')->with('zones', $zones);
    }

    protected function registerClub(Request $request)
    {
        $data = $request->all();

        //dd($data);

        if($data['password'] != $data['confirm_password']):
            return back()->withInput()->withErrors('La password di conferma non corrisponde');
        endif;

        $check_email = User::where('id_role', '=', 3)
                                ->where('email', '=', $data['email'])->first();
        if($check_email):
            return back()->withInput()->withErrors('L\'email inserita è già utilizzata');
        endif;

        $user = User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'id_role' => 3
        ]);

        if( $user ):

            if( isset($data['id_zone']) ):
                $id_zone = $data['id_zone'];
            else:
                $id_zone = null;
            endif;

            $club = Club::create ([
                'id_user' => $user->id,
                'id_city' => $data['id_city'],
                'name' => $data['club_name'],
                'address' => $data['indirizzo'],
                'mobile_phone' => $data['mobile_phone'],
            ]);


            /** Notify administrators about the new registration */
            // $users = User::where('id_role', '=', 1)->get();
            // foreach($users as $user_notify):
            //     $user_notify->notify(new NewUser($user));
            // endforeach;

        endif;

        Session::flash('message', 'L\'utenza è stata creata con successo. Verrai notificato non appena sarai stato abilitato dall\'amministratore');

        return redirect(route('welcome'));
    }
}
