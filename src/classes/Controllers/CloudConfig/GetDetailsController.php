<?php

namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Tools\CloudConfig\GetDetails;
use Symfony\Component\Routing\Attribute\Route;

class GetDetailsController
{
    public function __construct(
        private readonly GetDetails $getDetails
    ) {
    }

    #[Route(path: '/api/CloudConfig/GetDetailsController/get', name: 'Get cloud config details', methods: ['POST'])]
    public function get(int $id)
    {
        return $this->getDetails->get($id);
    }
}
