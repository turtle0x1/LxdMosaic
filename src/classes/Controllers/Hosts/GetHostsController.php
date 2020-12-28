<?php
namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;

class GetHostsController
{
    public function __construct(HostList $hostList, FetchUserDetails $fetchUserDetails)
    {
        $this->hostList = $hostList;
        $this->fetchUserDetails = $fetchUserDetails;
    }

    public function getAllHosts(int $userId)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId) === '1';
        if (!$isAdmin) {
            throw new \Exception("No access", 1);
        }
        return $this->hostList->getHostListWithDetails();
    }
}
