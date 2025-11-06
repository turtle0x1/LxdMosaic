<?php

namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules\UpdateBackupSchedules;
use dhope0000\LXDClient\Objects\Host;

class DeleteInstance
{
    public function __construct(
        private readonly UpdateBackupSchedules $updateBackupSchedules
    ) {
    }

    public function delete(int $userId, Host $host, string $instance)
    {
        $this->updateBackupSchedules->disableActiveScheds(
            $userId,
            $host->getHostId(),
            $instance,
            $host->getProject()
        );

        return $host->instances->remove($instance);
    }
}
