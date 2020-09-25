<?php
namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;

class SearchHosts
{
    public function __construct(HostList $hostList, HasExtension $hasExtension)
    {
        $this->hostList = $hostList;
        $this->hasExtension = $hasExtension;
    }

    public function search(string $hostSearch, array $extensionRequirements = [])
    {
        $servers = $this->hostList->getOnlineHostsWithDetails();
        $output = [];
        foreach ($servers as $server) {
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
