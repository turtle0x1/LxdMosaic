<?php

namespace dhope0000\LXDClient\Controllers\ProjectAnalytics;

use dhope0000\LXDClient\Tools\ProjectAnalytics\GetGraphableProjectAnalytics;
use Symfony\Component\Routing\Attribute\Route;

class GetGraphableProjectAnalyticsController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly GetGraphableProjectAnalytics $getGraphableProjectAnalytics
    ) {
    }

    #[Route(path: '/api/ProjectAnalytics/GetGraphableProjectAnalyticsController/get', name: 'Get graphable analytics', methods: ['POST'])]
    public function get(int $userId, string $history = '-30 minutes')
    {
        return $this->getGraphableProjectAnalytics->getCurrent($userId, $history);
    }
}
