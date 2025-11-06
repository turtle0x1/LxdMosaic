<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Users\InsertUser;
use dhope0000\LXDClient\Tools\User\Password\CheckPasswordPolicy;

class AddUser
{
    public function __construct(
        private readonly ValidatePermissions $validatePermissions,
        private readonly InsertUser $insertUser,
        private readonly FetchUserDetails $fetchUserDetails,
        private readonly CheckPasswordPolicy $checkPasswordPolicy
    ) {
    }

    public function add(int $userId, string $username, string $password)
    {
        $this->validatePermissions->isAdminOrThrow($userId);

        if ($this->fetchUserDetails->fetchHash($username) !== false) {
            throw new \Exception('Already have a user with this username', 1);
        }

        $this->checkPasswordPolicy->conforms($password);

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $this->insertUser->insert($username, $passwordHash);

        return true;
    }
}
