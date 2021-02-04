<?php

namespace dhope0000\LXDClient\Controllers\AnalyticData;

use dhope0000\LXDClient\Tools\Analytics\DownloadHistory;

class DownloadHistoryController
{
    public function __construct(DownloadHistory $downloadHistory)
    {
        $this->downloadHistory = $downloadHistory;
    }

    public function download(int $userId)
    {
        return  $this->downloadHistory->download($userId);
    }
}
