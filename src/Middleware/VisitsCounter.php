<?php namespace jlourenco\base\Middleware;

use Closure;
use Sentinel;
use Redirect;
use Base;

class VisitsCounter
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
        if (config('jlourenco.base.VisitCounter'))
            Base::RegisterVisit();

        return $next($request);
    }

}
