<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings;

use dhope0000\LXDClient\Tools\InstanceSettings\FirstRun;
use DI\Container;
use Symfony\Component\Routing\Attribute\Route;

class FirstRunController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly FirstRun $firstRun,
        private readonly Container $container
    ) {
    }

    #[Route(path: '/api/InstanceSettings/FirstRunController/run', name: 'Run LXDMosaic First Run', methods: ['POST'])]
    public function run(array $hosts, string $adminPassword, array $settings = [])
    {
        $this->container->call(["dhope0000\LXDClient\Model\Database\Database", 'beginTransaction']);
        $this->firstRun->run($hosts, $adminPassword, $settings);
        $this->container->call(["dhope0000\LXDClient\Model\Database\Database", 'commitTransaction']);
        return [
            'state' => 'success',
            'message' => 'Setup LXD Mosaic',
        ];
    }
}
