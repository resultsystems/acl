<?php

namespace ResultSystems\Acl\Middlewares;

use Auth;

class AuthOwnerMiddleware extends OwnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return int
     */
    public function handle($request)
    {
        $owner_id = env('acl.middleware.owner_id', 'owner_id');

        return Auth::user()->$owner_id;
    }
}
