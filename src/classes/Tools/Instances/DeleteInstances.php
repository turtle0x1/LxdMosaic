<?php
namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules\UpdateBackupSchedules;

class DeleteInstances
{
    public function __construct(UpdateBackupSchedules $updateBackupSchedules)
    {
        $this->updateBackupSchedules = $updateBackupSchedules;
    }

    public function delete(int $userId, Host $host, array $instances)
    {
        foreach ($instances as $instance) {
            $state = $host->instances->state($instance);

            if ($state["status_code"] == 103) {
                $host->instances->setState($instance, "stop");
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
