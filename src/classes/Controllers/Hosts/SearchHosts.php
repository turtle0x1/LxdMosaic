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

            $alias = is_null($server["Host_Alias"]) ? $server['Host_Url_And_Port'] : $server["Host_Alias"];
            if (strpos($alias, $host) !== false) {
                $output[] = [
                    "host"=>$alias,
                    "hostIp"=>$server["Host_Url_And_Port"],
                    "hostId"=>$server["Host_ID"]
                ];
            }
        }
        return $output;
    }
}
