<?php

namespace dhope0000\LXDClient\Tools\Hosts\Timers;

use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;

class GetTimersSnapshotData
{
    public function __construct(
        private readonly HostList $hostList,
        private readonly HasExtension $hasExtension
    ) {
    }

    public function get()
    {
        $hosts = $this->hostList->getOnlineHostsWithDetails();

        $output = [];

        foreach ($hosts as $host) {
            $supportsProjects = $this->hasExtension->checkWithHost($host, 'projects');
            $output[$host->getHostId()] = [];
            $allProjects = [[
                'name' => 'default',
                'config' => [],
            ]];

            if ($supportsProjects) {
                $allProjects = $host->projects->all(2);
            }

            foreach ($allProjects as $project) {
                $projectName = $project['name'];

                $output[$host->getHostId()][$projectName] = [];

                if ($supportsProjects) {
                    $host->setProject($projectName);
                }

                $instances = $host->instances->all(1);
                foreach ($instances as $instance) {
                    if ((int) $instance['status_code'] !== 103) {
                        continue;
                    }
                    $filePath = '/tmp/lxdmosaic-timer-script.sh';
                    $host->instances->files->write(
                        $instance['name'],
                        $filePath,
                        file_get_contents(__DIR__ . '/../../../../tools/instance_scripts/get_timers.sh'),
                        null,
                        null,
                        0777
                    );
                    $result = $host->instances->execute($instance['name'], $filePath, true, [], true);
                    $host->instances->files->remove($instance['name'], $filePath);
                    if ($result == null) {
                        continue;
                    }
                    $result = $host->instances->logs->read($instance['name'], $result['output'][0]);

                    $output[$host->getHostId()][$projectName][$instance['name']] = json_decode((string) $result, true);
                }
            }
        }
        return $output;
    }
}
