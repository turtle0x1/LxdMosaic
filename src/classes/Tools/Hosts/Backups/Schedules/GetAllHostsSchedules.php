<?php
namespace dhope0000\LXDClient\Tools\Hosts\Backups\Schedules;

use dhope0000\LXDClient\Tools\Hosts\GetClustersAndStandaloneHosts;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules\FetchBackupSchedules;

class GetAllHostsSchedules
{
    public function __construct(
        GetClustersAndStandaloneHosts $getClustersAndStandaloneHosts,
        FetchBackupSchedules $fetchBackupSchedules
    ) {
        $this->getClustersAndStandaloneHosts = $getClustersAndStandaloneHosts;
        $this->fetchBackupSchedules = $fetchBackupSchedules;
    }

    private function getSchedules(Host $host)
    {
        if (!$host->hostOnline()) {
            return [];
        }

        return $this->fetchBackupSchedules->fetchActive($host->getHostId());
    }


    public function get()
    {
        $clustersAndHosts = $this->getClustersAndStandaloneHosts->get(true);

        foreach ($clustersAndHosts["clusters"] as $clusterIndex => $cluster) {
            foreach ($cluster["members"] as $hostIndex => &$host) {
                $host->setCustomProp("schedules", $this->getSchedules($host));
            }
        }

        foreach ($clustersAndHosts["standalone"]["members"] as $index => &$host) {
            $host->setCustomProp("schedules", $this->getSchedules($host));
        }

        return $clustersAndHosts;
    }
}
