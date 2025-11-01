<?php
namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Tools\Hosts\GetHostsOverview;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use Symfony\Component\Routing\Annotation\Route;

class GetHostsController
{
    private $getHostsOverview;
    private $fetchUserDetails;
    
    public function __construct(GetHostsOverview $getHostsOverview, FetchUserDetails $fetchUserDetails)
    {
        $this->getHostsOverview = $getHostsOverview;
        $this->fetchUserDetails = $fetchUserDetails;
    }

    /**
     * @Route("/api/Hosts/GetHostsController/getAllHosts", name="api_hosts_gethostscontroller_getallhosts", methods={"POST"})
     */
    public function getAllHosts(int $userId)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);
        if (!$isAdmin) {
            throw new \Exception("No access", 1);
        }
        return $this->getHostsOverview->get();
    }
}
