<?php

namespace dhope0000\LXDClient\Tools\Deployments\Containers;

use dhope0000\LXDClient\Model\CloudConfig\GetConfig;

class GetContainersInDeployment
{
    public function __construct(
        private readonly GetConfig $getConfig
    ) {
    }

    public function getFromProfile(array $hostsWithProfiles)
    {
        $revCache = [];
        foreach ($hostsWithProfiles as &$host) {
            $host->setCustomProp('hostInfo', $host->host->info());
            $containers = [];
            foreach ($host->getCustomProp('profiles') as $profile) {
                $revId = $profile['revId'];
                foreach ($profile['usedBy'] as $item) {
                    if (str_contains((string) $item, '/1.0/containers') || str_contains(
                        (string) $item,
                        '/1.0/instances'
                    )) {
                        if (!isset($revCache[$revId])) {
                            $revInfo = $this->getConfig->getCloudConfigByRevId($revId);
                            $revCache[$revId] = $revInfo;
                        }

                        $revInfo = $revCache[$revId];

                        $containerName = str_replace('/1.0/containers/', '', $item);
                        $containerName = str_replace('/1.0/instances/', '', $item);

                        if (count(explode('/', $containerName)) > 1) {
                            continue;
                        }

                        $containerName = preg_replace("/\?project=.*/", '', $containerName);

                        $info = $host->instances->info($containerName);

                        if ($info['location'] !== 'none') {
                            if ($host->getCustomProp('hostInfo')['environment']['server_name'] !== $info['location']) {
                                continue;
                            }
                        }

                        $state = $host->instances->state($containerName);
                        $containers[] = [
                            'createdAt' => $info['created_at'],
                            'statusCode' => $info['status_code'],
                            'name' => $info['name'],
                            'type' => $revInfo['name'],
                            'state' => [
                                'network' => $state['network'],
                                'memory' => $state['memory'],
                            ],
                        ];
                    }
                }
            }
            $host->setCustomProp('containers', $containers);
        }
        return $hostsWithProfiles;
    }
}
