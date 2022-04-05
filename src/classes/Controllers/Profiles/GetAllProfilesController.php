<?php
namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Tools\Universe;
use Symfony\Component\Routing\Annotation\Route;

class GetAllProfilesController
{
    public function __construct(Universe $universe)
    {
        $this->universe = $universe;
    }
    /**
     * @Route("/api/Profiles/GetAllProfilesController/getAllProfiles", methods={"POST"}, name="Get all profiles on all hosts (deprecated)", options={ "deprecated" = "true", "rbac" = "profiles.read"})
     */
    public function getAllProfiles(int $userId)
    {
        return $this->universe->getEntitiesUserHasAccesTo($userId, "profiles");
    }
}
