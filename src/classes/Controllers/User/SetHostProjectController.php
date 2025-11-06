<?php

namespace dhope0000\LXDClient\Controllers\User;

use dhope0000\LXDClient\Tools\User\SetUserProject;
use Symfony\Component\Routing\Annotation\Route;

class SetHostProjectController
{
    public function __construct(
        private readonly SetUserProject $setUserProject
    ) {
    }

    /**
     * @Route("/api/User/SetHostProjectController/set", name="api_user_sethostprojectcontroller_set", methods={"POST"})
     */
    public function set(int $userId, int $hostId, string $project)
    {
        $this->setUserProject->set($userId, $hostId, $project);
        return [
            'state' => 'success',
            'message' => "Changed project to {$project}",
        ];
    }
}
