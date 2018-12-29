<?php
namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Model\Profiles\GetAllProfiles;

class GetAllProfilesController
{
    public function __construct(GetAllProfiles $getAllProfiles)
    {
        $this->getAllProfiles = $getAllProfiles;
    }

    public function getAllProfiles()
    {
        return $this->getAllProfiles->getAllProfiles();
    }
}
