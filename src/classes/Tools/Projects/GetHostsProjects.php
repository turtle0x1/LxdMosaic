<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Hosts\HostList;

class GetHostsProjects
{
    public function __construct(HostList $hostList, LxdClient $lxdClient)
    {
        $this->hostList = $hostList;
        $this->lxdClient = $lxdClient;
    }

    public function getAll() {
        $details = array();
        foreach ($this->hostList->getHostListWithDetails() as $host) {
            $client = $this->lxdClient->getANewClient($host["Host_ID"]);
            $projects = $client->projects->all();
            $details[$host["Host_Url_And_Port"]] = $projects;
        }
        return $details;
    }
}
