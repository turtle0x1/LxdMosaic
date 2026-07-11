<?php

namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Model\CloudConfig\GetConfigs;
use Symfony\Component\Routing\Attribute\Route;

class GetAllController
{
    public function __construct(
        private readonly GetConfigs $getConfigs
    ) {
    }

    #[Route(path: '/api/CloudConfig/GetAllController/getAll', name: 'Get all cloud configs', methods: ['POST'])]
    public function getAll()
    {
        return $this->getConfigs->getAll();
    }
}
