<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Tools\Projects\CreateProject;
use dhope0000\LXDClient\Objects\HostsCollection;
use Symfony\Component\Routing\Annotation\Route;

class CreateProjectController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private ValidatePermissions $validatePermissions;
    private CreateProject $createProject;

    public function __construct(ValidatePermissions $validatePermissions, CreateProject $createProject)
    {
        $this->validatePermissions = $validatePermissions;
        $this->createProject = $createProject;
    }
    /**
     * @Route("", name="Create Project")
     */
    public function create(
        int $userId,
        HostsCollection $hosts,
        string $name,
        string $description = "",
        array $config = []
    ) {
        $this->validatePermissions->isAdminOrThrow($userId);
        $this->createProject->create($hosts, $name, $description, $config);
        return ["state"=>"success", "message"=>"Created Projects"];
    }
}
