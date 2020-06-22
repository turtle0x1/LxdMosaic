<?php

namespace dhope0000\LXDClient\Tools\Instances\RecordedActions;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Model\InstanceSettings\RecordActions\FetchRecordedActions;
use dhope0000\LXDClient\Objects\RouteToNameMapping;

class GetAllInstanceRecordedActions
{
    public function __construct(FetchRecordedActions $fetchRecordedActions, RouteToNameMapping $routeToNameMapping)
    {
        $this->fetchRecordedActions = $fetchRecordedActions;
        $this->routeToNameMapping = $routeToNameMapping;
    }

    public function get(Host $host, string $instance)
    {
        $logs = $this->fetchRecordedActions->fetchForHostInstance($host->getHostId(), $instance);
        foreach ($logs as &$log) {
            $log["controllerName"] = $this->routeToNameMapping->getControllerName($log["controller"]);
        }
        return $logs;
    }
}
