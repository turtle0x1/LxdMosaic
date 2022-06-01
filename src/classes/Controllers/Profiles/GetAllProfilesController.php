<?php
namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Tools\Universe;

class GetAllProfilesController
{
    private $universe;
    
    public function __construct(Universe $universe)
    {
        $this->universe = $universe;
    }

    public function getAllProfiles(int $userId)
    {
        return $this->universe->getEntitiesUserHasAccesTo($userId, "profiles");
    }
}
