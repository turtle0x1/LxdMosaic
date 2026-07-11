<?php

namespace dhope0000\LXDClient\Controllers\User;

use dhope0000\LXDClient\Tools\User\SetUserProject;
use Symfony\Component\Routing\Attribute\Route;

class SetHostProjectController
{
    public function __construct(
        private readonly SetUserProject $setUserProject
    ) {
    }

    #[Route(path: '/api/User/SetHostProjectController/set', name: 'Set host project', methods: ['POST'])]
    public function set(int $userId, int $hostId, string $project)
    {
        $this->setUserProject->set($userId, $hostId, $project);
        return [
            'state' => 'success',
            'message' => "Changed project to {$project}",
        ];
    }
}
