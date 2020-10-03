<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings\RecordActions;

use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Model\InstanceSettings\RecordActions\FetchRecordedActions;
use dhope0000\LXDClient\Objects\RouteToNameMapping;
use \DI\Container;

class GetActions
{
    private $validatePermissions;
    private $fetchRecordedActions;

    private $knowHowToConvert = [
        // Instances
        "dhope0000\LXDClient\Controllers\Instances\CreateController\create"=>"dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\Convertors\CreateInstance",
        "dhope0000\LXDClient\Controllers\Instances\DeleteInstanceController\delete"=>"dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\Convertors\DeleteInstance",
        // Backups
        "dhope0000\LXDClient\Controllers\Instances\Backups\ScheduleBackupController\schedule"=>"dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\Convertors\Backups\ScheduleBackup",
        "dhope0000\LXDClient\Controllers\Instances\Backups\DisableScheduledBackupsController\disable"=>"dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\Convertors\Backups\DisableBackupSchedule",
        // Projects
        "dhope0000\LXDClient\Controllers\Projects\CreateProjectController\create"=>"dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\Convertors\Projects\CreateProject",
        "dhope0000\LXDClient\Controllers\Projects\DeleteProjectController\delete"=>"dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\Convertors\Projects\DeleteProject",
        // Profiles
        "dhope0000\LXDClient\Controllers\Profiles\CreateProfileController\create"=>"dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\Convertors\Profiles\CreateProfile",
        "dhope0000\LXDClient\Controllers\Profiles\DeleteProfileController\delete"=>"dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\Convertors\Profiles\DeleteProfile",
    ];

    public function __construct(
        ValidatePermissions $validatePermissions,
        FetchRecordedActions $fetchRecordedActions,
        RouteToNameMapping $routeToNameMapping,
        Container $container
    ) {
        $this->validatePermissions = $validatePermissions;
        $this->fetchRecordedActions = $fetchRecordedActions;
        $this->routeToNameMapping = $routeToNameMapping;
        $this->container = $container;
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

    public function getUserActions(int $userId, int $targetUser, string $controller, int $ammount = 30)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        // var_dump($controller);
        $logs = $this->fetchRecordedActions->fetchUserActions($targetUser, $controller, $ammount);
        $actions = [];
        foreach ($logs as &$log) {
            if (isset($this->knowHowToConvert[$log["controller"]])) {
                $x = $this->container->make($this->knowHowToConvert[$log["controller"]]);
                $log["params"] = json_decode($log["params"], true);
                $actions[] = $x->convert($log);
            }
        }
        return $actions;
    }
}
