<?php
namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Model\Hosts\HostList;

class SearchHosts
{
    public function __construct(HostList $hostList)
    {
        $this->hostList = $hostList;
    }

    public function search(string $hostSearch)
    {
        $servers = $this->hostList->getOnlineHostsWithDetails();
        $output = [];
        foreach ($servers as $server) {
            if (stripos($server->getAlias(), $hostSearch) !== false) {
                $output[] = [
                    "host"=>$server->getAlias(),
                    "hostId"=>$server->getHostId()
                ];
            }
        }
        return $output;
    }
}
