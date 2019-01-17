<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Tools\Projects\CreateProject;

class CreateProjectController
{
    public function __construct(CreateProject $createProject)
    {
        $this->createProject = $createProject;
    }

    public function create(
        array $hosts,
        string $name,
        string $description = "",
        array $config = []
    ) {
        $this->createProject->create($hosts, $name, $description, $config);
        return ["state"=>"success", "message"=>"Created Projects"];
    }
}
