<?php

namespace dhope0000\LXDClient\Controllers\Instances\InstanceTypes;

use dhope0000\LXDClient\Tools\Instances\InstanceTypes\AddInstanceType;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use Symfony\Component\Routing\Annotation\Route;

class AddController
{
    public function __construct(
        private readonly ValidatePermissions $validatePermissions,
        private readonly AddInstanceType $addInstanceType
    ) {
    }

    /**
     * @Route("/api/Instances/InstanceTypes/AddController/add", name="api_instances_instancetypes_addcontroller_add", methods={"POST"})
     */
    public function add(int $userId, int $providerId, string $name, float $cpu, float $mem)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        $this->addInstanceType->add($userId, $providerId, $name, $cpu, $mem);
        return [
            'state' => 'success',
            'message' => 'Added instance type',
        ];
    }
}
