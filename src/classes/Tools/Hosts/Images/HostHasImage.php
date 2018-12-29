<?php

namespace dhope0000\LXDClient\Tools\Hosts\Images;

use Opensaucesystems\Lxd\Client;

class HostHasImage
{
    public function has(Client $client, $fingerPrint)
    {
        //NOTE The end point throws a 404 exception so we have to catch (sigh)
        try {
            return (bool) $client->images->info($fingerPrint);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
