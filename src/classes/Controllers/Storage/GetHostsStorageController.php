<?php

namespace dhope0000\LXDClient\Controllers\Storage;

use dhope0000\LXDClient\Tools\Storage\GetUserStorage;
use Symfony\Component\Routing\Annotation\Route;

class GetHostsStorageController
{
    private $getUserStorage;
    
    public function __construct(GetUserStorage $getUserStorage)
    {
        $this->getUserStorage = $getUserStorage;
    }

    /**
     * @Route("/api/Storage/GetHostsStorageController/get", name="api_storage_gethostsstoragecontroller_get", methods={"POST"})
     */
    public function get($userId)
    {
        return $this->getUserStorage->getAll($userId);
    }
}
