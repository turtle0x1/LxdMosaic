<?php

namespace dhope0000\LXDClient\Controllers\Instances\InstanceTypes\Providers;

use \DI\Container;
use dhope0000\LXDClient\Tools\Instances\InstanceTypes\Providers\RemoveProvider;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class RemoveController
{
    private $validatePermissions;
    private $removeProvider;
    private $container;

    public function __construct(
        Container $container,
        ValidatePermissions $validatePermissions,
        RemoveProvider $removeProvider
    ) {
        $this->container = $container;
        $this->validatePermissions = $validatePermissions;
        $this->removeProvider = $removeProvider;
    }

    public function remove(int $userId, int $providerId)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        $this->container->call(["dhope0000\LXDClient\Model\Database\Database", "beginTransaction"]);
        $this->removeProvider->remove($providerId);
        $this->container->call(["dhope0000\LXDClient\Model\Database\Database", "commitTransaction"]);
        return ["state"=>"success", "message"=>"Deleted instance type provider"];
    }
}
