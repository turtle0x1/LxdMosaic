<?php

namespace dhope0000\LXDClient\Controllers\Instances\RecordedActions;

use dhope0000\LXDClient\Tools\Instances\RecordedActions\GetAllInstanceRecordedActions;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetHostInstanceEventsController
{
    private GetAllInstanceRecordedActions $getAllInstanceRecordedActions;

    public function __construct(GetAllInstanceRecordedActions $getAllInstanceRecordedActions)
    {
        $this->getAllInstanceRecordedActions = $getAllInstanceRecordedActions;
    }
    /**
     * @Route("", name="Get Instance Recorded Actions")
     */
    public function get(Host $host, string $container)
    {
        return $this->getAllInstanceRecordedActions->get($host, $container);
    }
}
