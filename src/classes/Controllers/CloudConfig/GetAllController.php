<?php
namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Model\CloudConfig\GetConfigs;

class GetAllController
{
    public function __construct(GetConfigs $getConfigs)
    {
        $this->getConfigs = $getConfigs;
    }

    public function getAll()
    {
        return $this->getConfigs->getAll();
    }
}
