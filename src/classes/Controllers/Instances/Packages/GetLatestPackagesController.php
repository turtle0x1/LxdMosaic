<?php

namespace dhope0000\LXDClient\Controllers\Instances\Packages;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Packages\GetLatestPackages;
use Symfony\Component\Routing\Attribute\Route;

class GetLatestPackagesController
{
    public function __construct(
        private readonly GetLatestPackages $getLatestPackages
    ) {
    }

    #[Route(path: '/api/instances/packages/latest', name: 'Get instance latest packages', methods: ['POST', 'GET'])]
    public function get(Host $host, string $container)
    {
        return $this->getLatestPackages->get($host, $container);
    }
}
