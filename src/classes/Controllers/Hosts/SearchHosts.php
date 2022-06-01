<?php
namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;

class SearchHosts
{
    private $hostList;
    private $hasExtension;
    private $fetchUserDetails;
    private $fetchAllowedProjects;
    
    public function __construct(
        HostList $hostList,
        HasExtension $hasExtension,
        FetchUserDetails $fetchUserDetails,
        FetchAllowedProjects $fetchAllowedProjects
    ) {
        $this->hostList = $hostList;
        $this->hasExtension = $hasExtension;
        $this->fetchUserDetails = $fetchUserDetails;
        $this->fetchAllowedProjects = $fetchAllowedProjects;
    }

    public function search(int $userId, string $hostSearch, array $extensionRequirements = [])
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId) === "1";

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
            if (stripos($server->getAlias(), $hostSearch) !== false) {
                $doesntHaveExt = false;
                if (!empty($extensionRequirements)) {
                    foreach ($extensionRequirements as $requirement) {
                        if (!$this->hasExtension->checkWithHost($server, $requirement)) {
                            $doesntHaveExt = true;
                            break;
                        }
                    }
                }
                if ($doesntHaveExt) {
                    continue;
                }
                $output[] = [
                    "host"=>$server->getAlias(),
                    "hostId"=>$server->getHostId()
                ];
            }
        }
        return $output;
    }
}
