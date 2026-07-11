<?php

namespace dhope0000\LXDClient\Controllers\Instances\InstanceTypes;

use dhope0000\LXDClient\Model\Instances\InstanceTypes\DeleteInstanceType;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use Symfony\Component\Routing\Attribute\Route;

class DeleteController
{
    public function __construct(
        private readonly ValidatePermissions $validatePermissions,
        private readonly DeleteInstanceType $deleteInstanceType
    ) {
    }

    #[Route(path: '/api/Instances/InstanceTypes/DeleteController/delete', name: 'Delete instance type', methods: ['POST'])]
    public function delete(int $userId, int $typeId)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        $this->deleteInstanceType->delete($typeId);
        return [
            'state' => 'success',
            'message' => 'Deleted instance type',
        ];
    }
}
