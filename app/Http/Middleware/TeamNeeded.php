<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Auth;

use App\Models\Role;
use App\Models\User;

class TeamNeeded
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
        $user_id = Auth::id();

        $user = User::find($user_id);            
        //dd($user);
        if(count($user->teams) > 0){                    
            return $next($request);                
        }else{
            return redirect()->route('admin.teams.create');
        }
    }
}
