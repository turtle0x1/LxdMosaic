<?php

namespace dhope0000\LXDClient\Controllers\AnalyticData;

use dhope0000\LXDClient\Tools\Analytics\DownloadHistory;
use Symfony\Component\Routing\Annotation\Route;

class DownloadHistoryController
{
    private $downloadHistory;
    
    public function __construct(DownloadHistory $downloadHistory)
    {
        $this->downloadHistory = $downloadHistory;
    }

    /**
     * @Route("/api/AnalyticData/DownloadHistoryController/download", name="api_analyticdata_downloadhistorycontroller_download", methods={"POST"})
     */
    public function download(int $userId)
    {
        return  $this->downloadHistory->download($userId);
    }
}
