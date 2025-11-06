<?php

namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Tools\CloudConfig\Create;
use Symfony\Component\Routing\Attribute\Route;

class CreateController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly Create $create
    ) {
    }

    #[Route(path: '/api/CloudConfig/CreateController/create', name: 'Create Cloud Config', methods: ['POST'])]
    public function create(string $name, string $namespace, $description = '')
    {
        $this->create->create($name, $namespace, $description);
        return [
            'state' => 'success',
            'message' => 'Created cloud config',
        ];
    }
}
