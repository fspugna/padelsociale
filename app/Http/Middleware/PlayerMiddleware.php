<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

use App\Models\Role;
use App\Models\User;

class PlayerMiddleware
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
            
            if($user){                                        
                // And after use `hasRoleName()` for check it
                if ($user->role()->first()->name === 'player'){                                
                    return $next($request);
                }else{                    
                    return redirect()->route('admin.home')->withErrors('Devi essere un giocatore');
                }
            }else{
                return redirect()->route('login', ['previous' => $request->fullUrl()]);
            }
        }else{
            return redirect()->route('login', ['previous' => $request->fullUrl()]);
        }
    }
}
