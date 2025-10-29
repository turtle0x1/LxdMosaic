<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings\ImageServers;

use dhope0000\LXDClient\Model\InstanceSettings\ImageServers\DeleteImageServer;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use Symfony\Component\Routing\Annotation\Route;

class DeleteImageServerController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $fetchUserDetails;
    private $deleteImageServer;

    public function __construct(
        FetchUserDetails $fetchUserDetails,
        DeleteImageServer $deleteImageServer
    ) {
        $this->fetchUserDetails = $fetchUserDetails;
        $this->deleteImageServer = $deleteImageServer;
    }
    /**
     * @Route("", name="Delete an image server")
     */
    public function delete(string $userId, string $alias)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);
        if (!$isAdmin) {
            throw new \Exception("No access", 1);
        }
        $this->deleteImageServer->delete($alias);
        return ["state" => "success", "message" => "Deleted image server"];
    }
}
