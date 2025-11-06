<?php

namespace dhope0000\LXDClient\Controllers\Instances\InstanceTypes\Providers;

use dhope0000\LXDClient\Tools\Instances\InstanceTypes\Providers\AddProvider;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use DI\Container;
use Symfony\Component\Routing\Annotation\Route;

class AddController
{
    public function __construct(
        private readonly Container $container,
        private readonly ValidatePermissions $validatePermissions,
        private readonly AddProvider $addProvider
    ) {
    }

    /**
     * @Route("/api/Instances/InstanceTypes/Providers/AddController/add", name="api_instances_instancetypes_providers_addcontroller_add", methods={"POST"})
     */
    public function add(int $userId, string $name)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        $this->container->call(["dhope0000\LXDClient\Model\Database\Database", 'beginTransaction']);
        $this->addProvider->add($userId, $name);
        $this->container->call(["dhope0000\LXDClient\Model\Database\Database", 'commitTransaction']);
        return [
            'state' => 'success',
            'message' => 'Added instance type provider',
        ];
    }
}
