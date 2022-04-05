<?php
namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Tools\Profiles\GetProfile;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetProfileController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(GetProfile $getProfile)
    {
        $this->getProfile = $getProfile;
    }
    /**
     * @Route("/api/Profiles/GetProfileController/get", methods={"POST"},  name="Get Profile", options={"rbac" = "profiles.read"})
     */
    public function get(int $userId, Host $host, string $profile)
    {
        return $this->getProfile->get($userId, $host, $profile);
    }
}
