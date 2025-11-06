<?php

namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules\UpdateBackupSchedules;
use dhope0000\LXDClient\Objects\Host;

class DeleteInstances
{
    public function __construct(
        private readonly UpdateBackupSchedules $updateBackupSchedules
    ) {
    }

    public function delete(int $userId, Host $host, array $instances)
    {
        foreach ($instances as $instance) {
            $state = $host->instances->state($instance);

            if ($state['status_code'] == 103) {
                $host->instances->setState($instance, 'stop', 30, true, false, true);
            }

            $this->updateBackupSchedules->disableActiveScheds(
                $userId,
                $host->getHostId(),
                $instance,
                $host->getProject()
            );

            $host->instances->remove($instance, true);
        }

        return true;
    }
}
