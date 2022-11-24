<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

use App\Models\Role;
use App\Models\User;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {        
        if( Auth::id() ){

            $user_id = Auth::id();

            $user = User::find($user_id);                    

            // And after use `hasRoleName()` for check it
            if ($user->role()->get()[0]->name === 'administrator'){
                return $next($request);
            }else{          
                /*
                if( Auth::id() ){
                    return redirect()->route('admin.home');
                } else {
                    return redirect()->route('welcome');
                }
                */
                return redirect()->route('welcome');
            }
        } else {
            return redirect()->route('welcome');
        }
    }
}
