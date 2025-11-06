<?php

namespace dhope0000\LXDClient\Controllers\Profiles\Search;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Profiles\GetProfilesOnAllHosts;
use Symfony\Component\Routing\Attribute\Route;

class SearchProfiles
{
    public function __construct(
        private readonly GetProfilesOnAllHosts $getProfilesOnAllHosts
    ) {
    }

    #[Route(path: '/api/Profiles/Search/SearchProfiles/getAllCommonProfiles', name: 'api_profiles_search_searchprofiles_getallcommonprofiles', methods: ['POST'])]
    public function getAllCommonProfiles(int $userId, string $profile)
    {
        return $this->getProfilesOnAllHosts->getProfilesOnAllHosts($userId, $profile);
    }

    #[Route(path: '/api/Profiles/Search/SearchProfiles/searchHostProfiles', name: 'api_profiles_search_searchprofiles_searchhostprofiles', methods: ['POST'])]
    public function searchHostProfiles(Host $host, string $search)
    {
        $profiles = $host->profiles->all();
        $output = [];
        foreach ($profiles as $profile) {
            if (stripos((string) $profile, $search) === false) {
                continue;
            }
            $output[] = [
                'name' => $profile,
            ];
        }
        return $output;
    }
}
