<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings\ImageServers;

use dhope0000\LXDClient\Model\InstanceSettings\ImageServers\FetchImageServers;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use Symfony\Component\Routing\Annotation\Route;

class GetAllImageServersListController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $fetchUserDetails;
    private $fetchImageServers;

    public function __construct(
        FetchUserDetails $fetchUserDetails,
        FetchImageServers $fetchImageServers)
    {
        $this->fetchUserDetails = $fetchUserDetails;
        $this->fetchImageServers = $fetchImageServers;
    }
    /**
     * @Route("", name="Get all image servers list")
     */
    public function all(string $userId)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);
        if (!$isAdmin) {
            throw new \Exception("No access", 1);
        }
        $aliases = $this->fetchImageServers->fetchAll();
        return ["state" => "success", "servers" => $aliases];
    }
}
