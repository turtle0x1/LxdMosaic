<?php

namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Constants\LxdApiExtensions;
use dhope0000\LXDClient\Constants\LxdRecursionLevels;
use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;

class GetHostsInstances
{
    public function __construct(
        private readonly HostList $hostList,
        private readonly HasExtension $hasExtension
    ) {
    }

    public function getAll()
    {
        $hosts = $this->hostList->getOnlineHostsWithDetails();
        foreach ($hosts as &$host) {
            $hostInfo = $host->host->info();

            $instances = $this->getContainers($host);

            $supportsBackups = $this->hasExtension->checkWithHost($host, LxdApiExtensions::CONTAINER_BACKUP);

            $host->setCustomProp('containers', $instances);
            $host->setCustomProp('supportsBackups', $supportsBackups);
            $host->setCustomProp('hostInfo', $hostInfo);
        }
        return $hosts;
    }

    public function getContainers(Host $host)
    {
        $recur = $this->hasExtension->checkWithHost($host, LxdApiExtensions::CONTAINER_FULL);

        $recur = $recur ? LxdRecursionLevels::INSTANCE_FULL_RECURSION : LxdRecursionLevels::INSTANCE_NO_RECURSION;

        $instances = $host->instances->all($recur);

        $instances = $this->addInstancesStateAndInfo($host, $instances);

        ksort($instances, SORT_STRING | SORT_FLAG_CASE);
        return $instances;
    }

    private function addInstancesStateAndInfo($host, $instances)
    {
        $hostInfo = $host->host->info();

        foreach ($instances as $index => $instance) {
            if (is_string($instance)) {
                $instance = $host->instances->info($instance);
                $instance['state'] = $host->instances->state($instance['name']);
            } else {
                // Keep the return between get all and using LXD recusion method
                // the above is slow enough lets not force it to add +2 api
                // calls to match this array
                unset($instance['backups']);
                unset($instance['snapshots']);
            }

            unset($instances[$index]);

            if ($instance['location'] !== '') {
                if ($instance['location'] !== 'none' && $instance['location'] !== $hostInfo['environment']['server_name']) {
                    continue;
                }
            }

            $instances[$instance['name']] = $instance;
        }

        return $instances;
    }
}
