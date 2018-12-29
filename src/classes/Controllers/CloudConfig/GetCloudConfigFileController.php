<?php
namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Model\CloudConfig\GetConfigFile;

class GetCloudConfigFileController
{
    public function getFile($folder, $file)
    {
        return GetConfigFile::getConfigFile($folder, $file);
    }
}
