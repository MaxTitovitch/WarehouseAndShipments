<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class HttpsProtocol
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
        // TODO https
//        if (!$request->secure()) {
//            return redirect()->secure($request->getRequestUri());
//        }
//
//        if (substr($request->header('host'), 0, 4) === 'www.') {
//            return redirect(env('APP_URL'));
//        }

        return $next($request);
    }
}
