<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Model\Deployments\FetchDeployments;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;

class GetDeployments
{
    public function __construct(
        private readonly FetchDeployments $fetchDeployments,
        private readonly GetDeployment $getDeployment,
        private readonly FetchUserDetails $fetchUserDetails
    ) {
    }

    public function getAll(int $userId)
    {
        $deploymentList = [];

        if ($this->fetchUserDetails->isAdmin($userId) !== '1') {
            $deploymentList = $this->fetchDeployments->fetchUserHasAccessTo($userId);
        } else {
            $deploymentList = $this->fetchDeployments->fetchAll();
        }

        return $this->addDetails($userId, $deploymentList);
    }

    private function addDetails($userId, $deploymentList)
    {
        foreach ($deploymentList as $index => $deployment) {
            $details = $this->getDeployment->get($userId, $deployment['id']);
            $containerDetails = $this->getContainerDetails($details['containers']);
            $deploymentList[$index]['containerDetails'] = $containerDetails;
        }
        return $deploymentList;
    }

    private function getContainerDetails($containerDetails)
    {
        $totalMem = 0;
        $totalContainers = 0;
        foreach ($containerDetails as $host) {
            if (!$host->hostOnline()) {
                continue;
            }
            $containers = $host->getCustomProp('containers');
            $totalContainers += count($containers);
            foreach ($containers as $container) {
                $totalMem += $container['state']['memory']['usage'];
            }
        }
        return [
            'totalMem' => $totalMem,
            'totalContainers' => $totalContainers,
        ];
    }
}
