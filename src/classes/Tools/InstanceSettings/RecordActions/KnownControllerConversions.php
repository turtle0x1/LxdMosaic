<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings\RecordActions;

class KnownControllerConversions
{
    private $knowHowToConvert = [
        // Instances
        "dhope0000\LXDClient\Controllers\Instances\CreateController\create"=>"dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\Convertors\Instances\CreateInstance",
        "dhope0000\LXDClient\Controllers\Instances\DeleteInstanceController\delete"=>"dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\Convertors\Instances\DeleteInstance",
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

    public function getAllControllers()
    {
        return array_keys($this->knowHowToConvert);
    }

    public function getConvertorClass(string $controller)
    {
        if (!isset($this->knowHowToConvert[$controller])) {
            throw new \Exception("Trying to convert a controller we dont know to convert", 1);
        }

        return $this->knowHowToConvert[$controller];
    }
}
