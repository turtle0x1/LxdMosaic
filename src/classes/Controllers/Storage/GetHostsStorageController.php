<?php

namespace dhope0000\LXDClient\Controllers\Storage;

use dhope0000\LXDClient\Tools\Storage\GetUserStorage;

class GetHostsStorageController
{
    private $getUserStorage;
    
    public function __construct(GetUserStorage $getUserStorage)
    {
        $this->getUserStorage = $getUserStorage;
    }

    public function get($userId)
    {
        return $this->getUserStorage->getAll($userId);
    }
}
