<?php

namespace dhope0000\LXDClient\Controllers\Instances\Files;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Files\GetPath;
use Symfony\Component\Routing\Attribute\Route;

class GetPathController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly GetPath $getPath
    ) {
    }

    #[Route(path: '/api/Instances/Files/GetPathController/get', name: 'Get Instance Path Contents', methods: ['POST'])]
    public function get(Host $host, string $container, string $path, $download)
    {
        return $this->getPath->get($host, $container, $path, (int) $download);
    }
}
