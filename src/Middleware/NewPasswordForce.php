<?php namespace jlourenco\base\Middleware;

use Closure;
use Sentinel;
use Redirect;

class NewPasswordForce
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
        if (($user = Sentinel::check()) && $request->route()->uri() != 'change-password' && $request->route()->uri() != 'logout')
        {
            if ($user->force_new_password)
            {
                if ($request->ajax())
                    return response('New password is required. Login into the website.', 401);
                else
                    return Redirect::route('change-password');
            }
        }
        return $next($request);
    }

}
