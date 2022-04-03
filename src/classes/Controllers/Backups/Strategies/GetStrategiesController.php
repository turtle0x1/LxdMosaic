<?php

namespace dhope0000\LXDClient\Controllers\Backups\Strategies;

use Symfony\Component\Routing\Annotation\Route;
use dhope0000\LXDClient\Model\Hosts\Backups\Strategies\FetchStrategies;

class GetStrategiesController
{
    private $fetchStrategies;

    public function __construct(FetchStrategies $fetchStrategies)
    {
        $this->fetchStrategies = $fetchStrategies;
    }
    /**
     * @Route("/api/Backups/Strategies/GetStrategiesController/get", methods={"POST"}, name="Get a list of backup strategies")
     */
    public function get()
    {
        return $this->fetchStrategies->fetchAll();
    }
}
