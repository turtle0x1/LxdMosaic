<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings\RecordedActions;

use dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\GetActions;
use Symfony\Component\Routing\Annotation\Route;

class GetLastController
{
    private $getActions;

    public function __construct(GetActions $getActions)
    {
        $this->getActions = $getActions;
    }
    /**
     * @Route("/api/InstanceSettings/RecordedActions/GetLastController/get", methods={"POST"}, name="Get N last number of actions recorded on LXDMosaic", options={"rbac" = "lxdmosaic.audit.read"})
     */
    public function get(int $userId, int $ammount)
    {
        return $this->getActions->get($userId, $ammount);
    }
}
