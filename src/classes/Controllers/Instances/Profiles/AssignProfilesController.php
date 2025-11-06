<?php

namespace dhope0000\LXDClient\Controllers\Instances\Profiles;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Profiles\AssignProfiles;
use Symfony\Component\Routing\Attribute\Route;

class AssignProfilesController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly AssignProfiles $assignProfiles
    ) {
    }

    #[Route(path: '/api/Instances/Profiles/AssignProfilesController/assign', name: 'Assign profiles to instance', methods: ['POST'])]
    public function assign(Host $host, string $container, array $profiles)
    {
        $this->assignProfiles->assign($host, $container, $profiles);

        return [
            'state' => 'success',
            'message' => 'Assigned profiles',
        ];
    }
}
