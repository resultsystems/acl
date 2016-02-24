<?php

namespace ResultSystems\Acl\Middlewares;

use Auth;

class AuthBranchMiddleware extends BranchMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return int
     */
    public function handle($request)
    {
        $branch_id = env('acl.middleware.branch_id', 'branch_id');

        return Auth::user()->$branch_id;
    }
}
