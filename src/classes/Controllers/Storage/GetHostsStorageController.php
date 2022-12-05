<?php

namespace dhope0000\LXDClient\Controllers\Storage;

use dhope0000\LXDClient\Tools\Storage\GetUserStorage;

class GetHostsStorageController
{
    private GetUserStorage $getUserStorage;

    public function __construct(GetUserStorage $getUserStorage)
    {
        $this->getUserStorage = $getUserStorage;
    }

    public function get(int $userId)
    {
        return $this->getUserStorage->getAll($userId);
    }
}
