<?php

namespace dhope0000\LXDClient\Tools\Hosts\Images;

use dhope0000\LXDClient\Objects\Host;

class HostHasImage
{
    public function has(Host $host, string $fingerPrint)
    {
        //NOTE The end point throws a 404 exception so we have to catch (sigh)
        try {
            return (bool) $host->images->info($fingerPrint);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
