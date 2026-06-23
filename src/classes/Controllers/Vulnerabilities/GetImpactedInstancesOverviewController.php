<?php

namespace dhope0000\LXDClient\Controllers\Vulnerabilities;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Tools\Vulnerabilities\Scan\GetImpactedInstancesOverview;
use Symfony\Component\Routing\Attribute\Route;

class GetImpactedInstancesOverviewController
{
    public function __construct(
        private readonly FetchUserDetails $fetchUserDetails,
        private readonly GetImpactedInstancesOverview $getImpactedInstancesOverview,
    ) {
    }

    #[Route(path: '/api/Vulnerabilities/GetImpactedInstancesOverviewController/get', name: 'api_vulnerabilities_getimpactedinstancesoverviewcontroller_get', methods: ['POST'])]
    public function get(int $userId)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);
        if (!$isAdmin) {
            throw new \Exception('No access', 1);
        }

        return $this->getImpactedInstancesOverview->get();
    }
}
