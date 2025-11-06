<?php

namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Profiles\GetProfile;
use Symfony\Component\Routing\Attribute\Route;

class GetProfileController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly GetProfile $getProfile
    ) {
    }

    #[Route(path: '/api/Profiles/GetProfileController/get', methods: ['POST'], name: 'Get Profile')]
    public function get(int $userId, Host $host, string $profile)
    {
        return $this->getProfile->get($userId, $host, $profile);
    }
}
