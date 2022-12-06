<?php

namespace dhope0000\LXDClient\Controllers\AnalyticData;

use dhope0000\LXDClient\Tools\Analytics\DownloadHistory;

class DownloadHistoryController
{
    private DownloadHistory $downloadHistory;

    public function __construct(DownloadHistory $downloadHistory)
    {
        $this->downloadHistory = $downloadHistory;
    }

    public function download(int $userId) :array
    {
        return  $this->downloadHistory->download($userId);
    }
}
