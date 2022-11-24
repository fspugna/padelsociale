<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use Flash;

use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->middleware('guest')->except('logout');
    }

    /**
     * @return string
     */
    public function redirectTo()
    {
        if ($this->request->has('previous')) {
            $this->redirectTo = $this->request->get('previous');
        }

        return $this->redirectTo ?? 'admin/home';
    }

    public function showLoginForm(){

        $formdata = [ 'login' => [
                        'method' => 'post',
                        'action' => '/login'],
                       'register' => [
                            'method' => 'post',
                            'action' => '/register']
                    ];

        return view('login')->with('formdata', $formdata);

    }

    public function login(Request $request){

        $input = $request->all();

        switch($input['role']){
            case 'player': $id_role = 2; break;
            case 'club': $id_role = 3; break;
            case 'partner': $id_role = 4; break;
            case 'admin': $id_role = 1; break;
        }

        $email = $request['email'];
        $password = $request['password'];

        // Effettuo prima la login autenticandomi con l'email
        if (Auth::attempt(['email' => $email, 'password' => $password, 'id_role' => $id_role, 'status' => 1])) {

            // Authentication passed...
            if (Auth::user()->id_role != 1) {
                return redirect()->route('welcome');
            }else{
                return redirect()->route('admin.home');
            }

        }else{

            // Se l'utente ha inserito il telefono al posto dell'email effettuo la login con il telefono
            if (Auth::attempt(['mobile_phone' => $email, 'password' => $password, 'id_role' => $id_role, 'status' => 1])) {

                // Authentication passed...
                if (Auth::user()->id_role != 1) {
                    return redirect()->route('welcome');
                }else{
                    return redirect()->route('admin.home');
                }

            }else{

                if( strpos($email, '@') !== false ):

                    $user = User::where('email', $email)
                                ->where('id_role', $id_role)
                                ->first();
                else:

                    $user = User::where('mobile_phone', $email)
                                ->where('id_role', $id_role)
                                ->first();

                endif;

                if( $user ){
                    if( $user->status == 0 ){
                        return redirect()->route('login')->withErrors(['Utente non attivo']);
                    }else{
                        return redirect()->route('login')->withErrors(['Password errata']);
                    }
                }else{
                    return redirect()->route('login')->withErrors(['Utente inesistente']);
                }

            }

        }
    }


}
