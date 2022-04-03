<?php
namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Model\CloudConfig\GetConfigFile;
use Symfony\Component\Routing\Annotation\Route;

class GetCloudConfigFileController
{
    /**
     * @Route("/api/CloudConfig/GetCloudConfigFileController/getFile", methods={"POST"}, name="Get cloud config file (deprecated)", options={"deprecated" = "true"})
     */
    public function getFile($folder, $file)
    {
        return GetConfigFile::getConfigFile($folder, $file);
    }
}
