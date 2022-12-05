<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\Projects\InsertUserProject;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class SetUserProject
{
    private InsertUserProject $insertUserProject;
    private ValidatePermissions $validatePermissions;

    public function __construct(
        InsertUserProject $insertUserProject,
        ValidatePermissions $validatePermissions
    ) {
        $this->insertUserProject = $insertUserProject;
        $this->validatePermissions = $validatePermissions;
    }

    public function set(int $userId, int $hostId, string $project) :void
    {
        $this->validatePermissions->canAccessHostProjectOrThrow($userId, $hostId, $project);

        $this->insertUserProject->insert($userId, $hostId, $project);
    }
}
