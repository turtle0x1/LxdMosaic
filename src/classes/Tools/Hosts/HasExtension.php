<?php

namespace dhope0000\LXDClient\Tools\Hosts;

use dhope0000\LXDClient\Objects\Host;

class HasExtension
{
    private array $hostCache = [];

    public function checkWithHost(Host $host, string $extension) :bool
    {
        $hostUrl = $host->getUrl();
        $info = isset($this->hostCache[$hostUrl]) ? $this->hostCache[$hostUrl] : $host->host->info();
        $this->hostCache[$hostUrl] = $info;
        return in_array($extension, $info["api_extensions"]);
    }
}
