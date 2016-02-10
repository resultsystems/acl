<?php

namespace ResultSystems\Acl\Handlers;

class ForbiddenHandler
{
    public function handle()
    {
        return response('forbidden', 403);
    }
}
