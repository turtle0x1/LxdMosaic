<?php

namespace dhope0000\LXDClient\Tools\Hosts;

use Opensaucesystems\Lxd\Client;
use dhope0000\LXDClient\Objects\Host;

class HasExtension
{
    private $hostCache = [];

    public function checkWithHost(Host $host, $extension)
    {
        $hostUrl = $host->getUrl();
        $info = isset($this->hostCache[$hostUrl]) ? $this->hostCache[$hostUrl] : $host->host->info();
        $this->hostCache[$hostUrl] = $info;
        return in_array($extension, $info["api_extensions"]);
    }
}
