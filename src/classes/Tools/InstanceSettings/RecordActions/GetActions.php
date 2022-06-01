<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings\RecordActions;

use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Model\InstanceSettings\RecordActions\FetchRecordedActions;
use dhope0000\LXDClient\Objects\RouteToNameMapping;

class GetActions
{
    private $validatePermissions;
    private $fetchRecordedActions;
    private $routeToNameMapping;

    public function __construct(
        ValidatePermissions $validatePermissions,
        FetchRecordedActions $fetchRecordedActions,
        RouteToNameMapping $routeToNameMapping
    ) {
        $this->validatePermissions = $validatePermissions;
        $this->fetchRecordedActions = $fetchRecordedActions;
        $this->routeToNameMapping = $routeToNameMapping;
    }

    public function get(int $userId, int $ammount)
    {
        $this->validatePermissions->isAdminOrThrow($userId);

        $logs = $this->fetchRecordedActions->fetch($ammount);
        foreach ($logs as &$log) {
            $log["controllerName"] = $this->routeToNameMapping->getControllerName($log["controller"]);
        }
        return $logs;
    }
}
