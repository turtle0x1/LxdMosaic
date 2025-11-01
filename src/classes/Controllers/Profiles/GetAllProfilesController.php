<?php
namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Tools\Universe;
use Symfony\Component\Routing\Annotation\Route;

class GetAllProfilesController
{
    private $universe;
    
    public function __construct(Universe $universe)
    {
        $this->universe = $universe;
    }

    /**
     * @Route("/api/Profiles/GetAllProfilesController/getAllProfiles", name="api_profiles_getallprofilescontroller_getallprofiles", methods={"POST"})
     */
    public function getAllProfiles(int $userId)
    {
        return $this->universe->getEntitiesUserHasAccesTo($userId, "profiles");
    }
}
