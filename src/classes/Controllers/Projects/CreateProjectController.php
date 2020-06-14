<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Tools\Projects\CreateProject;
use dhope0000\LXDClient\Objects\HostsCollection;

class CreateProjectController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(CreateProject $createProject)
    {
        $this->createProject = $createProject;
    }

    public function create(
        HostsCollection $hosts,
        string $name,
        string $description = "",
        array $config = []
    ) {
        $this->createProject->create($hosts, $name, $description, $config);
        return ["state"=>"success", "message"=>"Created Projects"];
    }
}
