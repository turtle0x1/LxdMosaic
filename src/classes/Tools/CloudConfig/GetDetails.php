<?php

namespace dhope0000\LXDClient\Tools\CloudConfig;

use dhope0000\LXDClient\Model\CloudConfig\GetConfig;
use dhope0000\LXDClient\Tools\CloudConfig\Contents\GetLatest;

class GetDetails
{
    public function __construct(
        private readonly GetConfig $getConfig,
        private readonly GetLatest $getLatest
    ) {
    }

    public function get(int $cloudConfigId)
    {
        return [
            'header' => $this->getConfig->getHeader($cloudConfigId),
            'data' => $this->getLatest->getLatest($cloudConfigId),
        ];
    }
}
