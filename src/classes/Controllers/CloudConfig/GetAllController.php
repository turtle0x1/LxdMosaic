<?php
namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Model\CloudConfig\GetConfigs;
use Symfony\Component\Routing\Annotation\Route;

class GetAllController
{
    private $getConfigs;
    
    public function __construct(GetConfigs $getConfigs)
    {
        $this->getConfigs = $getConfigs;
    }

    /**
     * @Route("/api/CloudConfig/GetAllController/getAll", name="api_cloudconfig_getallcontroller_getall", methods={"POST"})
     */
    public function getAll()
    {
        return $this->getConfigs->getAll();
    }
}
