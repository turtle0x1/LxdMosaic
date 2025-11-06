<?php

namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Objects\HostsCollection;
use dhope0000\LXDClient\Tools\Profiles\Copy;
use Symfony\Component\Routing\Attribute\Route;

class CopyProfileController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly Copy $copy
    ) {
    }

    #[Route(path: '/api/Profiles/CopyProfileController/copyProfile', name: 'Copy profile', methods: ['POST'])]
    public function copyProfile(Host $host, string $profile, HostsCollection $targetHosts, string $newName)
    {
        $response = $this->copy->copyToTargetHosts($host, $profile, $targetHosts, $newName);
        return [
            'state' => 'success',
            'message' => 'Copied Profile',
            'lxdResponse' => $response,
        ];
    }
}
