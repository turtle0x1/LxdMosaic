<?php
namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Model\CloudConfig\GetConfigs;
use Symfony\Component\Routing\Annotation\Route;

class GetAllController
{
    public function __construct(GetConfigs $getConfigs)
    {
        $this->getConfigs = $getConfigs;
    }
    /**
     * @Route("/api/CloudConfig/GetAllController/getAll", methods={"POST"}, name="Get all cloud configs")
     */
    public function getAll()
    {
        return $this->getConfigs->getAll();
    }
}
