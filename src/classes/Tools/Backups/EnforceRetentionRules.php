<?php

namespace dhope0000\LXDClient\Tools\Backups;

use dhope0000\LXDClient\Model\Hosts\Backups\Instances\FetchInstanceBackups;
use dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules\FetchBackupSchedules;
use dhope0000\LXDClient\Tools\Instances\Backups\DeleteLocalBackup;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Constants\BackupStrategies;
use dhope0000\LXDClient\Model\Users\FetchUsers;

class EnforceRetentionRules
{
    private FetchBackupSchedules $fetchBackupSchedules;
    private FetchInstanceBackups $fetchInstanceBackups;
    private DeleteLocalBackup $deleteLocalBackup;
    private FetchUsers $fetchUsers;

    public function __construct(
        FetchInstanceBackups $fetchInstanceBackups,
        FetchBackupSchedules $fetchBackupSchedules,
        DeleteLocalBackup $deleteLocalBackup,
        FetchUsers $fetchUsers
    ) {
        $this->fetchBackupSchedules = $fetchBackupSchedules;
        $this->fetchInstanceBackups = $fetchInstanceBackups;
        $this->deleteLocalBackup = $deleteLocalBackup;
        $this->fetchUsers = $fetchUsers;
    }

    public function enforce() :void
    {
        $hostBackups = $this->fetchBackupSchedules->fetchActiveSchedsGroupedByHostId();

        foreach ($hostBackups as $hostId => $scheduleItems) {
            $adminUserId = $this->fetchUsers->fetchAnyAdminUserId();
            foreach ($scheduleItems as $item) {
                $instanceBackups  = [];

                if ($item["strategyId"] == BackupStrategies::IMPORT_AND_DELETE) {
                    $instanceBackups = $this->fetchInstanceBackups->fetchAll($hostId, $item["instance"]);
                    foreach ($instanceBackups as $i => $b) {
                        if ($b["dateDeleted"] !== null) {
                            unset($instanceBackups[$i]);
                        }
                    }
                    $instanceBackups = array_values($instanceBackups);
                }

                $backupCount = count($instanceBackups);

                if ($backupCount <= 1 || $backupCount < $item["scheduleRetention"]) {
                    continue;
                }

                $instanceBackups = array_reverse($instanceBackups);

                if ((new \DateTime($instanceBackups[0]["dateCreated"])) > (new \DateTime($instanceBackups[$backupCount - 1]["dateCreated"]))) {
                    throw new \Exception("Developer change fail - Backups in wrong order", 1);
                }

                $toRemoveCount = $backupCount - $item["scheduleRetention"];

                if ($item["strategyId"] == BackupStrategies::IMPORT_AND_DELETE) {
                    for ($i = 0; $i < $toRemoveCount; $i++) {
                        $this->deleteLocalBackup->delete($adminUserId, $instanceBackups[$i]["id"]);
                    }
                }
            }
        }
    }
}
