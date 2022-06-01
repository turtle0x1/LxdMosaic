<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Tools\Universe;

class GetHostsProjects
{
    private $universe;
    
    public function __construct(Universe $universe)
    {
        $this->universe = $universe;
    }

    public function getAll($userId)
    {
        return $this->universe->getEntitiesUserHasAccesTo($userId, "projects");
    }
}
