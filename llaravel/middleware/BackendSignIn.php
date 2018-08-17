<?php

namespace Longbang\Llaravel\Middleware;

use Closure;
use Backend\Facades\Backend;
use Backend\Facades\BackendAuth;

class BackendSignIn
{
    /**
     * Handle the given request and get the response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, Closure $next)
    {
        if(empty(BackendAuth::check())){
            return Backend::redirect('backend/auth/signin');
        }
        return $next($request);
    }
}
