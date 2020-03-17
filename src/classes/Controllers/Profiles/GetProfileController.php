<?php
namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Tools\Profiles\GetProfile;

class GetProfileController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(GetProfile $getProfile)
    {
        $this->getProfile = $getProfile;
    }

    public function get(int $hostId, string $profile)
    {
        return $this->getProfile->get($hostId, $profile);
    }
}
