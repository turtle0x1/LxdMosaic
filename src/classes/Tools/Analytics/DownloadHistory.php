<?php

namespace dhope0000\LXDClient\Tools\Analytics;

use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Model\Analytics\FetchLatestData;

class DownloadHistory
{
    private $validatePermissions;
    private $fetchLatestData;
    
    public function __construct(
        ValidatePermissions $validatePermissions,
        FetchLatestData $fetchLatestData
    ) {
        $this->validatePermissions = $validatePermissions;
        $this->fetchLatestData = $fetchLatestData;
    }

    public function download(int $userId)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        return $this->fetchLatestData->fetchAll();
    }
}
