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
     * @Route("/api/InstanceSettings/RecordedActions/GetLastController/get", name="api_instancesettings_recordedactions_getlastcontroller_get", methods={"POST"})
     */
    public function get(int $userId, int $ammount)
    {
        return $this->getActions->get($userId, $ammount);
    }
}
