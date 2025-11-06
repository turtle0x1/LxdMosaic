<?php

namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Tools\Universe;
use Symfony\Component\Routing\Attribute\Route;

class GetAllProfilesController
{
    public function __construct(
        private readonly Universe $universe
    ) {
    }

    #[Route(path: '/api/Profiles/GetAllProfilesController/getAllProfiles', name: 'api_profiles_getallprofilescontroller_getallprofiles', methods: ['POST'])]
    public function getAllProfiles(int $userId)
    {
        return $this->universe->getEntitiesUserHasAccesTo($userId, 'profiles');
    }
}
