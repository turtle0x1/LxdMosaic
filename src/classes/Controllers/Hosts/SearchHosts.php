<?php
namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Model\Hosts\HostList;

class SearchHosts
{
    public function __construct(HostList $hostList)
    {
        $this->hostList = $hostList;
    }

    public function search()
    {
        $hosts = $this->hostList->getHostList();
        $output = [];
        //TODO Filter
        foreach ($hosts as $host) {
            $output[] = ["host"=>$host];
        }
        return $output;
    }
}
