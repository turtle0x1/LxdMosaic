<?php

namespace dhope0000\LXDClient\Controllers\Storage;

use dhope0000\LXDClient\Tools\Storage\GetUserStorage;
use Symfony\Component\Routing\Annotation\Route;

class GetHostsStorageController
{
    public function __construct(GetUserStorage $getUserStorage)
    {
        $this->getUserStorage = $getUserStorage;
    }
    /**
     * @Route("/api/Storage/GetHostsStorageController/get", methods={"POST"}, name="Get all stroage volumes available on all hosts", options={"rbac" = "storage.pools.read"})
     */
    public function get($userId)
    {
        return $this->getUserStorage->getAll($userId);
    }
}
