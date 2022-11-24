<?php

namespace App\Http\Middleware;

use Closure;

class ClubNeeded
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
        
        if(count($user->clubs) > 0){                    
            return $next($request);                
        }else{
            return redirect()->route('admin.clubs.create');
        }
    }
}
