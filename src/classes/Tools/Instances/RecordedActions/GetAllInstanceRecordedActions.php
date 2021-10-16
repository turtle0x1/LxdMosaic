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
        $paramsToRemove = [
            "host",
            "container",
            "instance",
            "userId"
        ];

        foreach ($logs as &$log) {
            $log["controllerName"] = $this->routeToNameMapping->getControllerName($log["controller"]);
            $d = json_decode($log["params"], true);
            foreach ($paramsToRemove as $toRemove) {
                if (isset($d[$toRemove])) {
                    unset($d[$toRemove]);
                }
            }
            $log["params"] = $d;
        }
        return $logs;
    }
}
