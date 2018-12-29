<?php

namespace dhope0000\LXDClient\Model\Images;

use dhope0000\LXDClient\Model\Client\LxdClient;

class ImportLinuxContainersByAlias
{
    public function __construct(
        LxdClient $lxdClient
    ) {
        $this->client = $lxdClient;
    }

    public function import($hosts, $aliases)
    {
        foreach ($hosts as $host) {
            $client = $this->client->getClientByUrl($host);
            foreach ($aliases as $alias) {
                $output[] = $client->images->createFromRemote(
                    "https://images.linuxcontainers.org:8443",
                    [
                        "alias"=>$alias
                    ]
                );
            }
        }
        return $output;
    }
}
