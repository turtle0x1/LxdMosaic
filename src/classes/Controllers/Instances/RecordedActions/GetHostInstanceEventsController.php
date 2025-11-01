<?php

namespace dhope0000\LXDClient\Controllers\Instances\RecordedActions;

use dhope0000\LXDClient\Tools\Instances\RecordedActions\GetAllInstanceRecordedActions;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetHostInstanceEventsController
{
    private $getAllInstanceRecordedActions;
    
    public function __construct(GetAllInstanceRecordedActions $getAllInstanceRecordedActions)
    {
        $this->getAllInstanceRecordedActions = $getAllInstanceRecordedActions;
    }
    /**
     * @Route("/api/Instances/RecordedActions/GetHostInstanceEventsController/get", name="Get Instance Recorded Actions", methods={"POST"})
     */
    public function get(Host $host, string $container)
    {
        return $this->getAllInstanceRecordedActions->get($host, $container);
    }
}
