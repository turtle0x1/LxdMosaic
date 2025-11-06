<?php

namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\CreateImage;
use Symfony\Component\Routing\Attribute\Route;

class CreateImageController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly CreateImage $createImage
    ) {
    }

    #[Route(path: '/api/Instances/CreateImageController/create', name: 'Create image from instance', methods: ['POST'])]
    public function create(
        Host $host,
        string $container,
        string $imageAlias,
        bool $public,
        ?string $os = null,
        ?string $description = null
    ) {
        $this->createImage->create($host, $container, $imageAlias, $public, $os, $description);
        return [
            'success' => 'Creating Image',
        ];
    }
}
