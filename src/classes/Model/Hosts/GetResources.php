<?php
namespace dhope0000\LXDClient\Model\Hosts;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use Symfony\Component\HttpFoundation\Session\Session;

class GetResources
{
    public function __construct(
        LxdClient $lxdClient,
        HostList $hostList,
        HasExtension $hasExtension,
        Session $session
    ) {
        $this->client = $lxdClient;
        $this->hostList = $hostList;
        $this->hasExtension = $hasExtension;
        $this->session = $session;
    }

    public function getHostResources($host)
    {
        return $this->client->getClientByUrl($host)->resources->info();
    }

    public function getAllHostRecourses()
    {
        $output = array();
        foreach ($this->hostList->getHostListWithDetails() as $host) {
            $hostId = $host["Host_ID"];
            $client = $this->client->getANewClient($hostId);
            $details = $client->resources->info();

            $supportsProjects = $this->hasExtension->checkWithClient($client, "projects");

            $details["hostId"] = $hostId;
            $details["alias"] = !is_null($host["Host_Alias"]) ? $host["Host_Alias"] : $host["Host_Url_And_Port"];

            $details["extensions"] = [
                "supportsProjects"=>$supportsProjects
            ];

            $details["projects"] = [];
            $details["currentProject"] = $this->session->get("host/$hostId/project");

            if ($supportsProjects) {
                $details["projects"] = $client->projects->all();
            }

            $output[$host["Host_Url_And_Port"]] = $details;
        }
        return $output;
    }
}
