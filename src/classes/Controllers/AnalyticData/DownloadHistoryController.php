<?php

namespace dhope0000\LXDClient\Controllers\AnalyticData;

use Symfony\Component\Routing\Annotation\Route;
use dhope0000\LXDClient\Tools\Analytics\DownloadHistory;

class DownloadHistoryController
{
    public function __construct(DownloadHistory $downloadHistory)
    {
        $this->downloadHistory = $downloadHistory;
    }
    /**
     * @Route("/api/AnalyticData/DownloadHistoryController/download", methods={"POST"}, name="Download old analytics data")
     */
    public function download(int $userId)
    {
        return  $this->downloadHistory->download($userId);
    }
}
