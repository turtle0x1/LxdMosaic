<?php

namespace dhope0000\LXDClient\Tools\CloudConfig;

use dhope0000\LXDClient\Model\CloudConfig\GetConfig;
use dhope0000\LXDClient\Tools\CloudConfig\Contents\GetLatest;

class GetDetails
{
    private $getConfig;
    private $getLatest;
    
    public function __construct(GetConfig $getConfig, GetLatest $getLatest)
    {
        $this->getConfig = $getConfig;
        $this->getLatest = $getLatest;
    }

    public function get(int $cloudConfigId)
    {
        return [
            "header"=>$this->getConfig->getHeader($cloudConfigId),
            "data"=>$this->getLatest->getLatest($cloudConfigId)
        ];
    }
}
