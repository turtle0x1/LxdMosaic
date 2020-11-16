<?php
namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Model\Hosts\HostList;

class GetHostsController
{
    public function __construct(HostList $hostList)
    {
        $this->hostList = $hostList;
    }
    /**
     * @TODO Secure
    */
    public function getAllHosts()
    {
        return $this->hostList->getHostListWithDetails();
    }
}
