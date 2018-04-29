<?php

namespace App\Http\Middleware;
use Auth;

use Closure;

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

        if(Auth::check())
        {
            if(Auth::user()->role == 'admin')
            {
                return $next($request);
            }
            else
            {
                // $request->session()->flash('message', 'You do not have access to this page!');
                return redirect('/'); 
            }
        }
        else
        {
            return redirect('/');
        }
    }
}
