<?php

namespace dhope0000\LXDClient\Controllers\Backups\Strategies;

use dhope0000\LXDClient\Model\Hosts\Backups\Strategies\FetchStrategies;
use Symfony\Component\Routing\Annotation\Route;

class GetStrategiesController
{
    private $fetchStrategies;

    public function __construct(FetchStrategies $fetchStrategies)
    {
        $this->fetchStrategies = $fetchStrategies;
    }

    /**
     * @Route("/api/Backups/Strategies/GetStrategiesController/get", name="api_backups_strategies_getstrategiescontroller_get", methods={"POST"})
     */
    public function get()
    {
        return $this->fetchStrategies->fetchAll();
    }
}
