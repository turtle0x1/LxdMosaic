<?php

namespace dhope0000\LXDClient\Controllers\Hosts\Warnings;

use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Attribute\Route;

class GetHostWarningsController
{
    #[Route(path: '/api/Hosts/Warnings/GetHostWarningsController/getOnHost', name: 'Get host warnings', methods: ['POST'])]
    public function getOnHost(Host $host)
    {
        return $host->warnings->all();
    }
}
