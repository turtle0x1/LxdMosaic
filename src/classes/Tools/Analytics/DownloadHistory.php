<?php

namespace dhope0000\LXDClient\Tools\Analytics;

use dhope0000\LXDClient\Model\Analytics\FetchLatestData;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class DownloadHistory
{
    public function __construct(
        private readonly ValidatePermissions $validatePermissions,
        private readonly FetchLatestData $fetchLatestData
    ) {
    }

    public function download(int $userId)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        return $this->fetchLatestData->fetchAll();
    }
}
