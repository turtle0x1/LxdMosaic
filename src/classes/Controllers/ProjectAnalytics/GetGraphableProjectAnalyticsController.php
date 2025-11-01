<?php

namespace dhope0000\LXDClient\Controllers\ProjectAnalytics;

use dhope0000\LXDClient\Tools\ProjectAnalytics\GetGraphableProjectAnalytics;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetGraphableProjectAnalyticsController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $getGraphableProjectAnalytics;
    
    public function __construct(GetGraphableProjectAnalytics $getGraphableProjectAnalytics)
    {
        $this->getGraphableProjectAnalytics = $getGraphableProjectAnalytics;
    }
    /**
     * @Route("/api/ProjectAnalytics/GetGraphableProjectAnalyticsController/get", name="Get graphable analytics", methods={"POST"})
     */
    public function get(int $userId, string $history = "-30 minutes")
    {
        return $this->getGraphableProjectAnalytics->getCurrent($userId, $history);
    }
}
