<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings\RecordActions;

use dhope0000\LXDClient\Model\InstanceSettings\RecordActions\FetchRecordedActions;
use dhope0000\LXDClient\Objects\RouteToNameMapping;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class GetActions
{
    public function __construct(
        private readonly ValidatePermissions $validatePermissions,
        private readonly FetchRecordedActions $fetchRecordedActions,
        private readonly RouteToNameMapping $routeToNameMapping
    ) {
    }

    public function get(int $userId, int $ammount)
    {
        $this->validatePermissions->isAdminOrThrow($userId);

        $logs = $this->fetchRecordedActions->fetch($ammount);
        foreach ($logs as &$log) {
            $log['controllerName'] = $this->routeToNameMapping->getControllerName($log['controller']);
        }
        return $logs;
    }
}
