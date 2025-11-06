<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\Projects\InsertUserProject;

class SetUserProject
{
    public function __construct(
        private readonly InsertUserProject $insertUserProject,
        private readonly ValidatePermissions $validatePermissions
    ) {
    }

    public function set(int $userId, int $hostId, string $project)
    {
        $this->validatePermissions->canAccessHostProjectOrThrow($userId, $hostId, $project);

        $this->insertUserProject->insert($userId, $hostId, $project);
    }
}
