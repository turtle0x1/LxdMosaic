<?php

namespace dhope0000\LXDClient\Controllers\Instances\Backups;

use dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules\UpdateBackupSchedules;

use dhope0000\LXDClient\Objects\Host;

class DisableScheduledBackupsController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $updateBackupSchedules;

    public function __construct(UpdateBackupSchedules $updateBackupSchedules)
    {
        $this->updateBackupSchedules = $updateBackupSchedules;
    }

    public function disable(
        int $userId,
        Host $host,
        string $instance
    ) {
        $this->updateBackupSchedules->disableActiveScheds(
            $userId,
            $host->getHostId(),
            $instance,
            "default"
        );

        return ["state"=>"success", "message"=>"Disabled schedule for instance"];
    }
}
