<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Tools\Universe;

class GetHostsProjects
{
    public function __construct(
        private readonly Universe $universe
    ) {
    }

    public function getAll($userId)
    {
        return $this->universe->getEntitiesUserHasAccesTo($userId, 'projects');
    }
}
