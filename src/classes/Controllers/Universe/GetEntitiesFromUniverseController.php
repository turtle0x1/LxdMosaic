<?php

namespace dhope0000\LXDClient\Controllers\Universe;

use dhope0000\LXDClient\Tools\Universe;
use Symfony\Component\Routing\Annotation\Route;

class GetEntitiesFromUniverseController
{
    private $universe;
    
    public function __construct(Universe $universe)
    {
        $this->universe = $universe;
    }

    /**
     * @Route("/api/Universe/GetEntitiesFromUniverseController/get", name="api_universe_getentitiesfromuniversecontroller_get", methods={"POST"})
     */
    public function get(int $userId, string $entity = null)
    {
        return $this->universe->getEntitiesUserHasAccesTo($userId, $entity);
    }
}
