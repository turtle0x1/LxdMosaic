<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Model\Deployments\FetchDeployments;
use dhope0000\LXDClient\Tools\Deployments\GetDeployment;

class GetDeployments
{
    public function __construct(
        FetchDeployments $fetchDeployments,
        GetDeployment $getDeployment
    ) {
        $this->fetchDeployments = $fetchDeployments;
        $this->getDeployment = $getDeployment;
    }

    public function getAll()
    {
        $deploymentList = $this->fetchDeployments->fetchAll();
        return $this->addDetails($deploymentList);
    }

    private function addDetails($deploymentList)
    {
        foreach ($deploymentList as $index =>$deployment) {
            $details = $this->getDeployment->get($deployment["id"]);
            $containerDetails = $this->getContainerDetails($details["containers"]);
            $deploymentList[$index]["containerDetails"] = $containerDetails;
        }
        return $deploymentList;
    }

    private function getContainerDetails($containerDetails)
    {
        $totalMem = 0;
        $totalContainers = 0;
        foreach ($containerDetails as $host => $details) {
            $totalContainers += count($details["containers"]);
            foreach ($details["containers"] as $container) {
                $totalMem += $container["state"]["memory"]["usage"];
            }
        }
        return [
            "totalMem"=>$totalMem,
            "totalContainers"=>$totalContainers
        ];
    }
}
