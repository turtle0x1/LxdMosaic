<?php

namespace dhope0000\LXDClient\Controllers\Backups\Strategies;

use dhope0000\LXDClient\Model\Hosts\Backups\Strategies\FetchStrategies;
use Symfony\Component\Routing\Attribute\Route;

class GetStrategiesController
{
    public function __construct(
        private readonly FetchStrategies $fetchStrategies
    ) {
    }

    #[Route(path: '/api/Backups/Strategies/GetStrategiesController/get', name: 'api_backups_strategies_getstrategiescontroller_get', methods: ['POST'])]
    public function get()
    {
        return $this->fetchStrategies->fetchAll();
    }
}
