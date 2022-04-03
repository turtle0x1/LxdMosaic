<?php

namespace dhope0000\LXDClient\Controllers\Universe;

use dhope0000\LXDClient\Tools\Universe;
use Symfony\Component\Routing\Annotation\Route;

class GetEntitiesFromUniverseController
{
    public function __construct(Universe $universe)
    {
        $this->universe = $universe;
    }
    /**
     * @Route("/api/Universe/GetEntitiesFromUniverseController/get", methods={"POST"}, name="Get specified entities on all hosts user has access to")
     */
    public function get(int $userId, string $entity = null)
    {
        return $this->universe->getEntitiesUserHasAccesTo($userId, $entity);
    }
}
