<?php
namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Model\CloudConfig\GetConfigs;

class GetAllController
{
    private GetConfigs $getConfigs;

    public function __construct(GetConfigs $getConfigs)
    {
        $this->getConfigs = $getConfigs;
    }

    public function getAll() :array
    {
        return $this->getConfigs->getAll();
    }
}
