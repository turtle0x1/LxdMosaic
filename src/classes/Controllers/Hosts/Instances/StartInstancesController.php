<?php

namespace dhope0000\LXDClient\Controllers\Hosts\Instances;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\StartInstances;
use Symfony\Component\Routing\Annotation\Route;

class StartInstancesController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly StartInstances $startInstances
    ) {
    }

    /**
     * @Route("/api/Hosts/Instances/StartInstancesController/start", name="Start Instances", methods={"POST"})
     */
    public function start(Host $host, array $containers)
    {
        $this->startInstances->start($host, $containers);
        return [
            'state' => 'success',
            'message' => 'Started Containers',
        ];
    }
}
