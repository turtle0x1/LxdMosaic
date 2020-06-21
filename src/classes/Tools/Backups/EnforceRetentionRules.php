<?php

namespace dhope0000\LXDClient\Tools\Backups;

use dhope0000\LXDClient\Model\Hosts\Backups\Instances\FetchInstanceBackups;
use dhope0000\LXDClient\Tools\Hosts\Backups\Schedules\GetAllHostsSchedules;
use dhope0000\LXDClient\Tools\Instances\Backups\DeleteLocalBackup;
use dhope0000\LXDClient\Objects\Host;

class EnforceRetentionRules
{
    private $getAllHostsSchedules;

    public function __construct(
        GetAllHostsSchedules $getAllHostsSchedules,
        FetchInstanceBackups $fetchInstanceBackups,
        DeleteLocalBackup $deleteLocalBackup
    ) {
        $this->getAllHostsSchedules = $getAllHostsSchedules;
        $this->fetchInstanceBackups = $fetchInstanceBackups;
        $this->deleteLocalBackup = $deleteLocalBackup;
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


        foreach ($scheduleItems as $item) {
            $instanceBackups  = [];
            if ($item["strategyId"] == 1) {
                $instanceBackups = [];
            } elseif ($item["strategyId"] == 2) {
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

            if ($item["strategyId"] == 2) {
                for ($i = 0; $i < $toRemoveCount; $i++) {
                    $this->deleteLocalBackup->delete($instanceBackups[$i]["id"]);
                }
            }
        }
    }
}
