<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Objects\HostsCollection;
use dhope0000\LXDClient\Tools\Projects\CreateProject;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use Symfony\Component\Routing\Attribute\Route;

class CreateProjectController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly ValidatePermissions $validatePermissions,
        private readonly CreateProject $createProject
    ) {
    }

    #[Route(path: '/api/Projects/CreateProjectController/create', name: 'Create Project', methods: ['POST'])]
    public function create(
        int $userId,
        HostsCollection $hosts,
        string $name,
        string $description = '',
        array $config = []
    ) {
        $this->validatePermissions->isAdminOrThrow($userId);
        $this->createProject->create($hosts, $name, $description, $config);
        return [
            'state' => 'success',
            'message' => 'Created Projects',
        ];
    }
}
