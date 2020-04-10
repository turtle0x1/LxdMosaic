<?php
namespace dhope0000\LXDClient\Tools\Hosts;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use Symfony\Component\HttpFoundation\Session\Session;
use \Opensaucesystems\Lxd\Client;

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

    public function getHostExtended(int $hostId)
    {
        return $this->getDetails($hostId);
    }

    public function getAllHostRecourses()
    {
        $output = array();
        foreach ($this->hostList->getHostListWithDetails() as $host) {
            $hostId = $host["Host_ID"];
            $details = [];

            $currentProject = $this->session->get("host/$hostId/project");

            if ($host["Host_Online"] != true) {
                $output[$host["Host_Url_And_Port"]] = [
                    "online"=>false,
                    "alias"=>$host["Host_Alias"],
                    "currentProject"=>$currentProject,
                    "hostId"=>$hostId
                ];
                continue;
            }


            $details = $this->getDetails($hostId);

            $details["alias"] = $host["Host_Alias"];
            $details["currentProject"] = $currentProject;
            $details["hostId"] = $hostId;


            $output[$host["Host_Url_And_Port"]] = $details;
        }
        return $output;
    }

    private function getDetails(int $hostId)
    {
        $client = $this->client->getANewClient($hostId);
        $details = $client->resources->info();

        $supportsProjects = $this->hasExtension->checkWithClient($client, "projects");
        $resCpuSocket = $this->hasExtension->checkWithClient($client, "resources_cpu_socket");
        $resGpu = $this->hasExtension->checkWithClient($client, "resources_gpu");

        $currentProject = $this->session->get("host/$hostId/project");

        $details["currentProject"] = $currentProject;

        $details["extensions"] = [
            "supportsProjects"=>$supportsProjects,
            "resCpuSocket"=>$resCpuSocket,
            "resGpu"=>$resGpu
        ];

        $details["projects"] = [];

        if ($supportsProjects) {
            $details["projects"] = $client->projects->all();
        }

        return $details;
    }
}
