<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings\RecordActions;

use dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\GetActions;

class GetUserOverview
{
    private $getActions;

    private $controllersToGet = [
        "dhope0000\LXDClient\Controllers\Instances\CreateController\create",
        "dhope0000\LXDClient\Controllers\Projects\CreateProjectController\create",
        "dhope0000\LXDClient\Controllers\Instances\DeleteInstanceController\delete",
        "dhope0000\LXDClient\Controllers\Instances\Backups\ScheduleBackupController\schedule",
        "dhope0000\LXDClient\Controllers\Instances\Backups\DisableScheduledBackupsController\disable"
    ];

    public function __construct(
        GetActions $getActions
    ) {
        $this->getActions = $getActions;
    }

    public function get(int $userId, int $targetUser)
    {
        $events = [];

        foreach ($this->controllersToGet as  $controller) {
            $actions = $this->getActions->getUserActions($userId, $targetUser, $controller);

            foreach ($actions as $action) {
                $cat = $action->getCategory();
                if (!isset($events[$cat])) {
                    $events[$cat] = [];
                }

                $method = $action->getMethod();
                if (!isset($events[$cat][$method])) {
                    $events[$cat][$method] = [];
                }

                $events[$cat][$method][] = $action;
            }
        }
        foreach ($events as $category => &$methods) {
            asort($methods);
        }
        return $events;
    }
}
