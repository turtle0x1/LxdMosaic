<?php

namespace dhope0000\LXDClient\Controllers\Storage;

use dhope0000\LXDClient\Objects\HostsCollection;
use dhope0000\LXDClient\Tools\Storage\GetStorageOnHosts;
use Symfony\Component\Routing\Annotation\Route;

class GetStoragePoolsOnHostsController
{
    private $getStorageOnHosts;
    
    public function __construct(GetStorageOnHosts $getStorageOnHosts)
    {
        $this->getStorageOnHosts = $getStorageOnHosts;
    }

    /**
     * @Route("/api/storage/pools/common", name="Given a list of hosts get storage available on all hosts", methods={"POST", "GET"})
     */
    public function get(HostsCollection $hosts)
    {
        return $this->getStorageOnHosts->getCommon($hosts);
    }
}
