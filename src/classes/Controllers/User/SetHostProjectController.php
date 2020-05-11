<?php

namespace dhope0000\LXDClient\Controllers\User;

use dhope0000\LXDClient\Model\Users\Projects\InsertUserProject;

class SetHostProjectController
{
    public function __construct(InsertUserProject $insertUserProject)
    {
        $this->insertUserProject = $insertUserProject;
    }

    public function set(int $userId, int $hostId, string $project)
    {
        $this->insertUserProject->insert($userId, $hostId, $project);
        return ["state"=>"success", "message"=>"Changed project to $project"];
    }
}
