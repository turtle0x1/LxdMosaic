<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Tools\Hosts\AddHosts;
use dhope0000\LXDClient\Tools\User\ResetPassword;
use dhope0000\LXDClient\Tools\User\AddUser;

class FirstRun
{
    private $fetchUserDetails;
    private $addHosts;
    private $adminUserId = 1;

    public function __construct(
        FetchUserDetails $fetchUserDetails,
        AddHosts $addHosts,
        ResetPassword $resetPassword,
        AddUser $addUser
    ) {
        $this->fetchUserDetails = $fetchUserDetails;
        $this->addHosts = $addHosts;
        $this->resetPassword = $resetPassword;
        $this->addUser = $addUser;
    }

    public function run(array $hosts, string $adminPassword, array $users = [])
    {
        if ($this->fetchUserDetails->adminPasswordBlank() !== true) {
            throw new \Exception("Cant run first run", 1);
        }

        $this->resetPassword->reset($this->adminUserId, $this->adminUserId, $adminPassword);

        foreach ($users as $user) {
            $this->validateUser($user);
            $this->addUser->add($this->adminUserId, $user["username"], $user["password"]);
        }

        $this->addHosts->add($this->adminUserId, $hosts);
    }

    private function validateUser($user)
    {
        if (!isset($user["username"]) || $user["username"] == "") {
            throw new \Exception("Username missing", 1);
        } elseif (!isset($user["password"]) || $user["password"] == "") {
            throw new \Exception("{$user["username"]} password is empty", 1);
        }
    }
}
