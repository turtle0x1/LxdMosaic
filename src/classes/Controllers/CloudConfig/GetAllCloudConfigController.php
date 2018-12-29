<?php
namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Model\CloudConfig\GetConfigFiles;

class GetAllCloudConfigController
{
    public function __construct(GetConfigFiles $getConfigFiles)
    {
        $this->getConfigFiles = $getConfigFiles;
    }

    public function getAllConfigs()
    {
        return $this->getConfigFiles->getAllConfigs();
    }
}
