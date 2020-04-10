<?php
namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Model\Hosts\HostList;

class SearchHosts
{
    public function __construct(HostList $hostList)
    {
        $this->hostList = $hostList;
    }

    public function search(string $host)
    {
        $servers = $this->hostList->getHostListWithDetails();
        $output = [];
        foreach ($servers as $server) {
            if ($server["Host_Online"] != true) {
                continue;
            }

            if (stripos($server["Host_Alias"], $host) !== false) {
                $output[] = [
                    "host"=>$server["Host_Alias"],
                    "hostId"=>$server["Host_ID"]
                ];
            }
        }
        return $output;
    }
}
