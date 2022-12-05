<?php

namespace dhope0000\LXDClient\Controllers\User;

use dhope0000\LXDClient\Tools\User\SetUserProject;

class SetHostProjectController
{
    private SetUserProject $setUserProject;

    public function __construct(SetUserProject $setUserProject)
    {
        $this->setUserProject = $setUserProject;
    }

    public function set(int $userId, int $hostId, string $project) :array
    {
        $this->setUserProject->set($userId, $hostId, $project);
        return ["state"=>"success", "message"=>"Changed project to $project"];
    }
}
