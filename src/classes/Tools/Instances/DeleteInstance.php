<?php
namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules\UpdateBackupSchedules;

class DeleteInstance
{
    private UpdateBackupSchedules $updateBackupSchedules;

    public function __construct(UpdateBackupSchedules $updateBackupSchedules)
    {
        $this->updateBackupSchedules = $updateBackupSchedules;
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
