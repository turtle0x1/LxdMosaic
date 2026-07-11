<?php

namespace dhope0000\LXDClient\Controllers\Universe;

use dhope0000\LXDClient\Tools\Universe;
use Symfony\Component\Routing\Attribute\Route;

class GetEntitiesFromUniverseController
{
    public function __construct(
        private readonly Universe $universe
    ) {
    }

    #[Route(path: '/api/Universe/GetEntitiesFromUniverseController/get', name: 'Get user entities', methods: ['POST'])]
    public function get(int $userId, ?string $entity = null)
    {
        return $this->universe->getEntitiesUserHasAccesTo($userId, $entity);
    }
}
