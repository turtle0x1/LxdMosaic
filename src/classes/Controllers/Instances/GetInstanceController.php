<?php

namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\GetInstance;
use Symfony\Component\Routing\Annotation\Route;

class GetInstanceController
{
    public function __construct(
        private readonly GetInstance $getInstance
    ) {
    }

    /**
     * @Route("/api/Instances/GetInstanceController/get", name="api_instances_getinstancecontroller_get", methods={"POST"})
     */
    public function get(Host $host, string $container)
    {
        return $this->getInstance->get($host, $container);
    }
}
