<?php

namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Constants\LxdInstanceTypes;
use dhope0000\LXDClient\Objects\HostsCollection;
use dhope0000\LXDClient\Tools\Instances\CreateInstance;
use Symfony\Component\Routing\Attribute\Route;

class CreateController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly CreateInstance $createInstance
    ) {
    }

    #[Route(path: '/api/Instances/CreateController/create', name: 'Create Instance', methods: ['POST'])]
    public function create(
        $name,
        HostsCollection $hosts,
        array $imageDetails,
        $profileIds = [],
        string $instanceType = '',
        $server = '',
        array $gpus = [],
        array $config = [],
        bool $start = false
    ) {
        $lxdResponses = $this->createInstance->create(
            LxdInstanceTypes::CONTAINER,
            $name,
            $profileIds,
            $hosts,
            $imageDetails,
            $server,
            $instanceType,
            $gpus,
            $config,
            $start
        );
        return [
            'success' => 'Created Container',
            'lxdResponses' => $lxdResponses,
        ];
    }
}
