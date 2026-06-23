<?php

namespace dhope0000\LXDClient\Controllers\Vulnerabilities;

use dhope0000\LXDClient\Model\Vulnerabilities\Vulnerability\FetchVulnerabilityDetails;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use Symfony\Component\Routing\Attribute\Route;

class SearchVulnerabilitiesController
{
    public function __construct(
        private readonly FetchUserDetails $fetchUserDetails,
        private readonly FetchVulnerabilityDetails $fetchVulnDetails,
    ) {
    }

    #[Route(path: '/api/Vulnerabilities/SearchVulnerabilitiesController/search', name: 'api_vulnerabilities_searchvulnerabilitiescontroller_search', methods: ['POST'])]
    public function search(int $userId, string $query)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);
        if (!$isAdmin) {
            throw new \Exception('No access', 1);
        }

        return $this->fetchVulnDetails->search($query);
    }
}
