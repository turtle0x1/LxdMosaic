<?php

namespace dhope0000\LXDClient\Exceptions\Users;

class CantFindUserException extends \Exception
{
    public function __construct($username)
    {
        parent::__construct("Cant find user account for $username");
    }
}
