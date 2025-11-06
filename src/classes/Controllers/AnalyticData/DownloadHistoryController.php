<?php

namespace dhope0000\LXDClient\Controllers\AnalyticData;

use dhope0000\LXDClient\Tools\Analytics\DownloadHistory;
use Symfony\Component\Routing\Annotation\Route;

class DownloadHistoryController
{
    public function __construct(
        private readonly DownloadHistory $downloadHistory
    ) {
    }

    /**
     * @Route("/api/AnalyticData/DownloadHistoryController/download", name="api_analyticdata_downloadhistorycontroller_download", methods={"POST"})
     */
    public function download(int $userId)
    {
        return $this->downloadHistory->download($userId);
    }
}
