<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings\ImageServers;

use dhope0000\LXDClient\Constants\ImageServerType;
use dhope0000\LXDClient\Tools\InstanceSettings\ImageServers\AddImageServer;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use Symfony\Component\Routing\Annotation\Route;

class AddImageServerController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $fetchUserDetails;
    private $addImageServer;

    public function __construct(
        FetchUserDetails $fetchUserDetails,
        AddImageServer $addImageServer
    ) {
        $this->fetchUserDetails = $fetchUserDetails;
        $this->addImageServer = $addImageServer;
    }
    /**
     * @Route("/api/InstanceSettings/ImageServers/AddImageServerController/add", name="Add a new image server", methods={"POST"})
     */
    public function add(string $userId, string $alias, string $url)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);
        if (!$isAdmin) {
            throw new \Exception("No access", 1);
        }
        $this->addImageServer->add($alias, $url, ImageServerType::SIMPLE_STREAMS);
        return ["state" => "success", "message" => "Added image server"];
    }
}
