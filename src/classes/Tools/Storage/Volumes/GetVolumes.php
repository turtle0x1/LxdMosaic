<?php

namespace dhope0000\LXDClient\Tools\Storage\Volumes;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Utilities\UsedByFilter;

class GetVolumes
{
    public function __construct(
        private readonly UsedByFilter $usedByFilter
    ) {
    }

    public function get(int $userId, Host $host)
    {
        $pools = $host->storage->all(2);

        $volumes = [];

        foreach ($pools as $pool) {
            $pool['used_by'] = $this->usedByFilter->filterUserProjects($userId, $host, $pool['used_by']);
            foreach ($pool['used_by'] as $item) {
                if (str_contains((string) $item, '/1.0/storage-pools/')) {
                    $url = parse_url((string) $item);
                    $project = 'default';
                    if (isset($url['query'])) {
                        parse_str($url['query'], $query);
                        if (isset($query['project'])) {
                            $project = $query['project'];
                        }
                    }

                    if ($host->getProject() !== $project) {
                        continue;
                    }

                    $parts = explode('/', $url['path']);
                    $name = $parts[count($parts) - 1];

                    $path = str_replace('volumes/', '', substr($url['path'], strpos($url['path'], '/volumes/') + 1));

                    $volumes[] = [
                        'pool' => $pool['name'],
                        'path' => $path,
                        'project' => $project,
                        'name' => $name,
                    ];
                }
            }
        }

        return $volumes;
    }
}
