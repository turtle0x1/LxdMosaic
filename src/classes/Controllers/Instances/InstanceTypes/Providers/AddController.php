<?php

namespace dhope0000\LXDClient\Controllers\Instances\InstanceTypes\Providers;

use \DI\Container;
use dhope0000\LXDClient\Tools\Instances\InstanceTypes\Providers\AddProvider;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use Symfony\Component\Routing\Annotation\Route;

class AddController
{
    private $validatePermissions;
    private $addProvider;
    private $container;

    public function __construct(
        Container $container,
        ValidatePermissions $validatePermissions,
        AddProvider $addProvider
    ) {
        $this->container = $container;
        $this->validatePermissions = $validatePermissions;
        $this->addProvider = $addProvider;
    }
    /**
     * @Route("/api/Instances/InstanceTypes/Providers/AddController/add", methods={"POST"}, name="Add instance type provider")
     */
    public function add(int $userId, string $name)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        $this->container->call(["dhope0000\LXDClient\Model\Database\Database", "beginTransaction"]);
        $this->addProvider->add($userId, $name);
        $this->container->call(["dhope0000\LXDClient\Model\Database\Database", "commitTransaction"]);
        return ["state"=>"success", "message"=>"Added instance type provider"];
    }
}
