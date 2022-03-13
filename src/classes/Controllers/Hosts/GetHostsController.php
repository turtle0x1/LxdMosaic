<?php
namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Tools\Hosts\GetHostsOverview;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;

class GetHostsController
{
    public function __construct(GetHostsOverview $getHostsOverview, FetchUserDetails $fetchUserDetails)
    {
        $this->getHostsOverview = $getHostsOverview;
        $this->fetchUserDetails = $fetchUserDetails;
    }

    public function getAllHosts(int $userId)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId) === '1';
        if (!$isAdmin) {
            throw new \Exception("No access", 1);
        }
        return $this->getHostsOverview->get();
    }
}
