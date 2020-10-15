<?php

namespace dhope0000\LXDClient\Tools\Hosts;

use dhope0000\LXDClient\Model\Hosts\DeleteHost;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;

class RemoveHost
{
    public function __construct(
        DeleteHost $deleteHost,
        FetchUserDetails $fetchUserDetails
    ) {
        $this->deleteHost = $deleteHost;
        $this->fetchUserDetails = $fetchUserDetails;
    }

    public function remove($userId, int $hostId)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId) === "1";

        if (!$isAdmin) {
            throw new \Exception("Not allowed to delete hosts", 1);
        }

        $this->deleteHost->delete($hostId);
    }
}
