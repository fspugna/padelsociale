<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Auth;

use App\Models\Role;
use App\Models\User;

class ClubMiddleware
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
            
        // And after use `hasRoleName()` for check it
        if ($user->role()->get()[0]->name === 'club'){
            return $next($request);
        }else{
            return redirect()->route('admin.home');
        }
    }
}
