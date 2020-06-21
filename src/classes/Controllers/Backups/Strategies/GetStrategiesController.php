<?php

namespace dhope0000\LXDClient\Controllers\Backups\Strategies;

use dhope0000\LXDClient\Model\Hosts\Backups\Strategies\FetchStrategies;

class GetStrategiesController
{
    private $fetchStrategies;

    public function __construct(FetchStrategies $fetchStrategies)
    {
        $this->fetchStrategies = $fetchStrategies;
    }

    public function get()
    {
        return $this->fetchStrategies->fetchAll();
    }
}
