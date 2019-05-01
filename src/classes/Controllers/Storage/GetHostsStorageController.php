<?php

namespace dhope0000\LXDClient\Controllers\Storage;

use dhope0000\LXDClient\Tools\Storage\GetHostsStorage;

class GetHostsStorageController
{
    public function __construct(GetHostsStorage $getHostsStorage)
    {
        $this->getHostsStorage = $getHostsStorage;
    }

    public function get(){
        return $this->getHostsStorage->getAll();
    }
}
