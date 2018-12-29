<?php
namespace dhope0000\LXDClient\Controllers\Profiles\Search;

use dhope0000\LXDClient\Model\Profiles\GetProfilesOnAllHosts;

class SearchProfiles
{
    public function __construct(GetProfilesOnAllHosts $getProfilesOnAllHosts)
    {
        $this->getProfilesOnAllHosts = $getProfilesOnAllHosts;
    }

    public function getAllCommonProfiles()
    {
        return $this->getProfilesOnAllHosts->getProfilesOnAllHosts();
    }
}
