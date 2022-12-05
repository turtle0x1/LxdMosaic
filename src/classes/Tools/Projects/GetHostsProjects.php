<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Tools\Universe;

class GetHostsProjects
{
    private Universe $universe;

    public function __construct(Universe $universe)
    {
        $this->universe = $universe;
    }

    public function getAll(int $userId) :array
    {
        return $this->universe->getEntitiesUserHasAccesTo($userId, "projects");
    }
}
