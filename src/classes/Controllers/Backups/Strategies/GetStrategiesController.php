<?php

namespace dhope0000\LXDClient\Controllers\Backups\Strategies;

use dhope0000\LXDClient\Model\Hosts\Backups\Strategies\FetchStrategies;

class GetStrategiesController
{
    private FetchStrategies $fetchStrategies;

    public function __construct(FetchStrategies $fetchStrategies)
    {
        $this->fetchStrategies = $fetchStrategies;
    }

    public function get() :array
    {
        return $this->fetchStrategies->fetchAll();
    }
}
