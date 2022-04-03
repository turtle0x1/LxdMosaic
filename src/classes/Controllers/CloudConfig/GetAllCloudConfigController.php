<?php
namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Model\CloudConfig\GetConfigFiles;
use Symfony\Component\Routing\Annotation\Route;

class GetAllCloudConfigController
{
    public function __construct(GetConfigFiles $getConfigFiles)
    {
        $this->getConfigFiles = $getConfigFiles;
    }
    /**
     * @Route("/api/CloudConfig/GetAllCloudConfigController/getAllConfigs", methods={"POST"}, name="Get all cloud configs (deprecated)", options={"deprecated" = "true"})
     */
    public function getAllConfigs()
    {
        return $this->getConfigFiles->getAllConfigs();
    }
}
