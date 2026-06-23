<?php

namespace dhope0000\LXDClient\Controllers\Vulnerabilities;

use dhope0000\LXDClient\Model\Vulnerabilities\Vulnerability\FetchVulnerabilityDetails;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use Symfony\Component\Routing\Attribute\Route;

class GetVulnerabilitiesController
{
    public function __construct(
        private readonly FetchUserDetails $fetchUserDetails,
        private readonly FetchVulnerabilityDetails $fetchVulnDetails,
    ) {
    }

    #[Route(path: '/api/Vulnerabilities/GetVulnerabilitiesController/get', name: 'api_vulnerabilities_getvulnerabilitiescontroller_get', methods: ['POST'])]
    public function get(int $userId, string $osFilter = '', int $limit = 100)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);
        if (!$isAdmin) {
            throw new \Exception('No access', 1);
        }

        $all = $this->fetchVulnDetails->fetchAll($osFilter ?: null);
        return array_slice($all, 0, $limit);
    }
}
