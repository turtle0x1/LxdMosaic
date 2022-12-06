<?php

namespace dhope0000\LXDClient\Exceptions\Users;

class PasswordIncorrectException extends \Exception
{
    public function __construct(string $username)
    {
        parent::__construct("Password incorrect for $username");
    }
}
