<?php

namespace dhope0000\LXDClient\Controllers\Universe;

use dhope0000\LXDClient\Tools\Universe;

class GetEntitiesFromUniverseController
{
    private $universe;
    
    public function __construct(Universe $universe)
    {
        $this->universe = $universe;
    }

    public function get(int $userId, string $entity = null)
    {
        return $this->universe->getEntitiesUserHasAccesTo($userId, $entity);
    }
}
