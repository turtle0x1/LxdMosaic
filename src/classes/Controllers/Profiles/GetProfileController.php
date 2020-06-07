<?php
namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Tools\Profiles\GetProfile;
use dhope0000\LXDClient\Objects\Host;

class GetProfileController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(GetProfile $getProfile)
    {
        $this->getProfile = $getProfile;
    }

    public function get(Host $host, string $profile)
    {
        return $this->getProfile->get($host, $profile);
    }
}
