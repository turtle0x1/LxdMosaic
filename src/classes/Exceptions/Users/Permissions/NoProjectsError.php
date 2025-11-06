<?php

namespace dhope0000\LXDClient\Exceptions\Users\Permissions;

class NoProjectsError extends PermissionsError
{
    public function __construct()
    {
        parent::__construct("User has no access projects");
    }
}