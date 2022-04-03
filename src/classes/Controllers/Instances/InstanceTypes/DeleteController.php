<?php

namespace dhope0000\LXDClient\Controllers\Instances\InstanceTypes;

use dhope0000\LXDClient\Model\Instances\InstanceTypes\DeleteInstanceType;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use Symfony\Component\Routing\Annotation\Route;

class DeleteController
{
    public function __construct(
        ValidatePermissions $validatePermissions,
        DeleteInstanceType $deleteInstanceType
    ) {
        $this->validatePermissions = $validatePermissions;
        $this->deleteInstanceType = $deleteInstanceType;
    }
    /**
     * @Route("/api/Instances/InstanceTypes/DeleteController/delete", methods={"POST"}, name="Delete instance type")
     */
    public function delete(int $userId, int $typeId)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        $this->deleteInstanceType->delete($typeId);
        return ["state"=>"success", "message"=>"Deleted instance type"];
    }
}
