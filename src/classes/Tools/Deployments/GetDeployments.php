<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Model\Deployments\FetchDeployments;
use dhope0000\LXDClient\Tools\Deployments\GetDeployment;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;

class GetDeployments
{
    private FetchDeployments $fetchDeployments;
    private GetDeployment $getDeployment;
    private FetchUserDetails $fetchUserDetails;

    public function __construct(
        FetchDeployments $fetchDeployments,
        GetDeployment $getDeployment,
        FetchUserDetails $fetchUserDetails
    ) {
        $this->fetchDeployments = $fetchDeployments;
        $this->getDeployment = $getDeployment;
        $this->fetchUserDetails = $fetchUserDetails;
    }

    public function getAll(int $userId) :array
    {
        $deploymentList = [];

        if ($this->fetchUserDetails->isAdmin($userId) !== "1") {
            $deploymentList = $this->fetchDeployments->fetchUserHasAccessTo($userId);
        } else {
            $deploymentList = $this->fetchDeployments->fetchAll();
        }

        return $this->addDetails($userId, $deploymentList);
    }

    private function addDetails(int $userId, array $deploymentList) :array
    {
        foreach ($deploymentList as $index =>$deployment) {
            $details = $this->getDeployment->get($userId, $deployment["id"]);
            $containerDetails = $this->getContainerDetails($details["containers"]);
            $deploymentList[$index]["containerDetails"] = $containerDetails;
        }
        return $deploymentList;
    }

    private function getContainerDetails(array $containerDetails) :array
    {
        $totalMem = 0;
        $totalContainers = 0;
        foreach ($containerDetails as $host) {
            if (!$host->hostOnline()) {
                continue;
            }
            $containers = $host->getCustomProp("containers");
            $totalContainers += count($containers);
            foreach ($containers as $container) {
                $totalMem += $container["state"]["memory"]["usage"];
            }
        }
        return [
            "totalMem"=>$totalMem,
            "totalContainers"=>$totalContainers
        ];
    }
}
