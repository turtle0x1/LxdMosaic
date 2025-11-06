<?php

namespace dhope0000\LXDClient\Controllers\Instances\InstanceTypes\Providers;

use dhope0000\LXDClient\Tools\Instances\InstanceTypes\Providers\RemoveProvider;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use DI\Container;
use Symfony\Component\Routing\Attribute\Route;

class RemoveController
{
    public function __construct(
        private readonly Container $container,
        private readonly ValidatePermissions $validatePermissions,
        private readonly RemoveProvider $removeProvider
    ) {
    }

    #[Route(path: '/api/Instances/InstanceTypes/Providers/RemoveController/remove', name: 'api_instances_instancetypes_providers_removecontroller_remove', methods: ['POST'])]
    public function remove(int $userId, int $providerId)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        $this->container->call(["dhope0000\LXDClient\Model\Database\Database", 'beginTransaction']);
        $this->removeProvider->remove($providerId);
        $this->container->call(["dhope0000\LXDClient\Model\Database\Database", 'commitTransaction']);
        return [
            'state' => 'success',
            'message' => 'Deleted instance type provider',
        ];
    }
}
