<?php
namespace dhope0000\LXDClient\Controllers\Profiles\Search;

use dhope0000\LXDClient\Tools\Profiles\GetProfilesOnAllHosts;
use dhope0000\LXDClient\Objects\Host;

class SearchProfiles
{
    private GetProfilesOnAllHosts $getProfilesOnAllHosts;

    public function __construct(GetProfilesOnAllHosts $getProfilesOnAllHosts)
    {
        $this->getProfilesOnAllHosts = $getProfilesOnAllHosts;
    }

    public function getAllCommonProfiles(int $userId, string $profile)
    {
        return $this->getProfilesOnAllHosts->getProfilesOnAllHosts($userId, $profile);
    }

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
