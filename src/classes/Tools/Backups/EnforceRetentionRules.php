<?php

namespace dhope0000\LXDClient\Tools\Backups;

use dhope0000\LXDClient\Constants\BackupStrategies;
use dhope0000\LXDClient\Model\Hosts\Backups\Instances\FetchInstanceBackups;
use dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules\FetchBackupSchedules;
use dhope0000\LXDClient\Model\Users\FetchUsers;
use dhope0000\LXDClient\Tools\Instances\Backups\DeleteLocalBackup;

class EnforceRetentionRules
{
    public function __construct(
        private readonly FetchInstanceBackups $fetchInstanceBackups,
        private readonly FetchBackupSchedules $fetchBackupSchedules,
        private readonly DeleteLocalBackup $deleteLocalBackup,
        private readonly FetchUsers $fetchUsers
    ) {
    }

    public function enforce()
    {
        $hostBackups = $this->fetchBackupSchedules->fetchActiveSchedsGroupedByHostId();

        foreach ($hostBackups as $hostId => $scheduleItems) {
            $adminUserId = $this->fetchUsers->fetchAnyAdminUserId();
            foreach ($scheduleItems as $item) {
                $instanceBackups = [];

                if ($item['strategyId'] == BackupStrategies::IMPORT_AND_DELETE) {
                    $instanceBackups = $this->fetchInstanceBackups->fetchAll($hostId, $item['instance']);
                    foreach ($instanceBackups as $i => $b) {
                        if ($b['dateDeleted'] !== null) {
                            unset($instanceBackups[$i]);
                        }
                    }
                    $instanceBackups = array_values($instanceBackups);
                }

                $backupCount = count($instanceBackups);

                if ($backupCount <= 1 || $backupCount < $item['scheduleRetention']) {
                    continue;
                }

                $instanceBackups = array_reverse($instanceBackups);

                if ((new \DateTime($instanceBackups[0]['dateCreated'])) > (new \DateTime(
                    $instanceBackups[$backupCount - 1]['dateCreated']
                ))) {
                    throw new \Exception('Developer change fail - Backups in wrong order', 1);
                }

                $toRemoveCount = $backupCount - $item['scheduleRetention'];

                if ($item['strategyId'] == BackupStrategies::IMPORT_AND_DELETE) {
                    for ($i = 0; $i < $toRemoveCount; $i++) {
                        $this->deleteLocalBackup->delete($adminUserId, $instanceBackups[$i]['id']);
                    }
                }
            }
        }
    }
}
