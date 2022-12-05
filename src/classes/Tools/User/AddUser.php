<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Model\Users\InsertUser;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Tools\User\Password\CheckPasswordPolicy;

class AddUser
{
    private ValidatePermissions $validatePermissions;
    private InsertUser $insertUser;
    private FetchUserDetails $fetchUserDetails;
    private CheckPasswordPolicy $checkPasswordPolicy;

    public function __construct(
        ValidatePermissions $validatePermissions,
        InsertUser $insertUser,
        FetchUserDetails $fetchUserDetails,
        CheckPasswordPolicy $checkPasswordPolicy
    ) {
        $this->validatePermissions = $validatePermissions;
        $this->insertUser = $insertUser;
        $this->fetchUserDetails = $fetchUserDetails;
        $this->checkPasswordPolicy = $checkPasswordPolicy;
    }

    public function add(int $userId, string $username, string $password) :bool
    {
        $this->validatePermissions->isAdminOrThrow($userId);

        if ($this->fetchUserDetails->fetchHash($username) !== false) {
            throw new \Exception("Already have a user with this username", 1);
        }

        $this->checkPasswordPolicy->conforms($password);

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $this->insertUser->insert($username, $passwordHash);

        return true;
    }
}
