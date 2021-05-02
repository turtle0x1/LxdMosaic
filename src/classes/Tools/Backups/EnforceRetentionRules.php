<?php

namespace dhope0000\LXDClient\Tools\Backups;

use dhope0000\LXDClient\Model\Hosts\Backups\Instances\FetchInstanceBackups;
use dhope0000\LXDClient\Tools\Hosts\Backups\Schedules\GetAllHostsSchedules;
use dhope0000\LXDClient\Tools\Instances\Backups\DeleteLocalBackup;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Constants\BackupStrategies;
use dhope0000\LXDClient\Model\Users\FetchUsers;

class EnforceRetentionRules
{
    private $getAllHostsSchedules;

    public function __construct(
        GetAllHostsSchedules $getAllHostsSchedules,
        FetchInstanceBackups $fetchInstanceBackups,
        DeleteLocalBackup $deleteLocalBackup,
        FetchUsers $fetchUsers
    ) {
        $this->getAllHostsSchedules = $getAllHostsSchedules;
        $this->fetchInstanceBackups = $fetchInstanceBackups;
        $this->deleteLocalBackup = $deleteLocalBackup;
        $this->fetchUsers = $fetchUsers;
    }

    public function enforce()
    {
        $clustersAndStandalone = $this->getAllHostsSchedules->get();

        foreach ($clustersAndStandalone["clusters"] as $cluster) {
            foreach ($cluster["members"] as $member) {
                $this->addSchedulesToSchedule($member);
            }
        }

        foreach ($clustersAndStandalone["standalone"]["members"] as $member) {
            $this->addSchedulesToSchedule($member);
        }
    }

    private function addSchedulesToSchedule($host)
    {
        $scheduleItems = $host->getCustomProp("schedules");

        $adminUserId = $this->fetchUsers->fetchAnyAdminUserId();

        foreach ($scheduleItems as $item) {
            $instanceBackups  = [];

            if ($item["strategyId"] == BackupStrategies::IMPORT_AND_DELETE) {
                $instanceBackups = $this->fetchInstanceBackups->fetchAll($item["hostId"], $item["instance"]);
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
