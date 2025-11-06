<?php

namespace dhope0000\LXDClient\Controllers\Projects\Users;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Projects\Users\GetUsersAccessToProject;
use Symfony\Component\Routing\Attribute\Route;

class GetUsersWithAccessController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly GetUsersAccessToProject $getUsersAccessToProject
    ) {
    }

    #[Route(path: '/api/Projects/Users/GetUsersWithAccessController/get', name: 'Get users with access to a host project', methods: ['POST'])]
    public function get(int $userId, Host $host, string $project)
    {
        return $this->getUsersAccessToProject->get($userId, $host, $project);
    }
}
