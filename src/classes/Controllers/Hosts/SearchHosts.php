<?php

namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use Symfony\Component\Routing\Attribute\Route;

class SearchHosts
{
    public function __construct(
        private readonly HostList $hostList,
        private readonly HasExtension $hasExtension,
        private readonly FetchUserDetails $fetchUserDetails,
        private readonly FetchAllowedProjects $fetchAllowedProjects
    ) {
    }

    #[Route(path: '/api/Hosts/SearchHosts/search', name: 'api_hosts_searchhosts_search', methods: ['POST'])]
    public function search(int $userId, string $hostSearch, array $extensionRequirements = [])
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);

        $projectsWithAccess = $this->fetchAllowedProjects->fetchAll($userId);

        if ($isAdmin === true) {
            $hosts = $this->hostList->fetchAllHosts();
        } else {
            $hosts = $this->hostList->getHostCollection(array_keys($projectsWithAccess));
        }

        $output = [];
        foreach ($hosts as $server) {
            if ($server->hostOnline() == false) {
                continue;
            }
            if (stripos((string) $server->getAlias(), $hostSearch) !== false) {
                $doesntHaveExt = false;
                if (!empty($extensionRequirements)) {
                    foreach ($extensionRequirements as $requirement) {
                        if (! $this->hasExtension->checkWithHost($server, $requirement)) {
                            $doesntHaveExt = true;
                            break;
                        }
                    }
                }
                if ($doesntHaveExt) {
                    continue;
                }
                $output[] = [
                    'host' => $server->getAlias(),
                    'hostId' => $server->getHostId(),
                ];
            }
        }
        return $output;
    }
}
