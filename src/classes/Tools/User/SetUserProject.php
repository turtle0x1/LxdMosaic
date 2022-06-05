<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\Projects\InsertUserProject;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class SetUserProject
{
    private $insertUserProject;
    private $validatePermissions;
    
    public function __construct(
        InsertUserProject $insertUserProject,
        ValidatePermissions $validatePermissions
    ) {
        $this->insertUserProject = $insertUserProject;
        $this->validatePermissions = $validatePermissions;
    }

    public function set(int $userId, int $hostId, string $project)
    {
        $this->validatePermissions->canAccessHostProjectOrThrow($userId, $hostId, $project);

        $this->insertUserProject->insert($userId, $hostId, $project);
    }
}
