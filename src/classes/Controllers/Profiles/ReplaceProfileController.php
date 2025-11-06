<?php

namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Profiles\Rename;
use Symfony\Component\Routing\Attribute\Route;

class ReplaceProfileController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly Rename $rename
    ) {
    }

    #[Route(path: '/api/Profiles/ReplaceProfileController/replace', name: 'Replace Profile', methods: ['POST'])]
    public function replace(
        Host $host,
        string $name,
        string $description,
        array $config = [],
        array $devices = []
    ) {
        $host->profiles->replace($name, $description, $config, $devices);
        return [
            'state' => 'success',
            'message' => 'Updated profile',
        ];
    }
}
