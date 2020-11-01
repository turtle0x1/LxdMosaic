<?php

namespace dhope0000\LXDClient\Tools\Networks;

use dhope0000\LXDClient\Tools\Universe;

class GetHostsNetworks
{
    public function __construct(Universe $universe)
    {
        $this->universe = $universe;
    }

    public function getAll($userId)
    {
        return $this->universe->getEntitiesUserHasAccesTo($userId, "networks");
    }
}
