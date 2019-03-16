<?php

namespace dhope0000\LXDClient\Controllers\Containers\InstanceTypes;

use dhope0000\LXDClient\Tools\Containers\InstanceTypes\GetInstanceTypes;

class GetAllController
{
    public function __construct(GetInstanceTypes $getInstanceTypes)
    {
        $this->getInstanceTypes = $getInstanceTypes;
    }

    public function getAll()
    {
        return $this->getInstanceTypes->getGroupedByProvider();
    }
}
