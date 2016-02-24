<?php

namespace ResultSystems\Acl\Middlewares;

use Tymon\JWTAuth\JWTAuth;

class JwtBranchMiddleware extends BranchMiddleware
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $auth;

    /**
     * Create a new BaseMiddleware instance.
     *
     * @param \Tymon\JWTAuth\JWTAuth  $auth
     */
    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Fire event and return the response.
     *
     * @param  string   $event
     * @param  string   $error
     * @param  int  $status
     * @param  array    $payload
     * @return mixed
     */
    protected function respond($event, $error, $status, $payload = [])
    {
        $response = $this->events->fire($event, $payload, true);

        return $response ?: $this->response->json(['error' => $error], $status);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return int
     */
    public function handle($request)
    {
        $token = $this->auth->setRequest($request)->getToken();

        $branch_id = env('acl.middleware.branch_id', 'branch_id');

        return $this->auth->getPayload($token)->get($branch_id);
    }
}
