<?php
namespace dhope0000\LXDClient\Controllers\Profiles\Search;

use dhope0000\LXDClient\Tools\Profiles\GetProfilesOnAllHosts;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class SearchProfiles
{
    private $getProfilesOnAllHosts;
    
    public function __construct(GetProfilesOnAllHosts $getProfilesOnAllHosts)
    {
        $this->getProfilesOnAllHosts = $getProfilesOnAllHosts;
    }

    /**
     * @Route("/api/Profiles/Search/SearchProfiles/getAllCommonProfiles", name="api_profiles_search_searchprofiles_getallcommonprofiles", methods={"POST"})
     */
    public function getAllCommonProfiles(int $userId, string $profile)
    {
        return $this->getProfilesOnAllHosts->getProfilesOnAllHosts($userId, $profile);
    }

    /**
     * @Route("/api/Profiles/Search/SearchProfiles/searchHostProfiles", name="api_profiles_search_searchprofiles_searchhostprofiles", methods={"POST"})
     */
    public function searchHostProfiles(Host $host, string $search)
    {
        $profiles = $host->profiles->all();
        $output = [];
        foreach ($profiles as $profile) {
            if (stripos($profile, $search) === false) {
                continue;
            }
            $output[] = ["name"=>$profile];
        }
        return $output;
    }
}
