<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Model\Users\InsertUser;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;

class AddUser
{
    public function __construct(ValidatePermissions $validatePermissions, InsertUser $insertUser, FetchUserDetails $fetchUserDetails)
    {
        $this->validatePermissions = $validatePermissions;
        $this->insertUser = $insertUser;
        $this->fetchUserDetails = $fetchUserDetails;
    }

    public function add(int $userId, string $username, string $password)
    {
        $this->validatePermissions->isAdminOrThrow($userId);

        if ($this->fetchUserDetails->fetchHash($username) !== false) {
            throw new \Exception("Already have a user with this username", 1);
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $this->insertUser->insert($username, $passwordHash);

        return true;
    }
}
