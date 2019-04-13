<?php

namespace dhope0000\LXDClient\Tools\Images;

use dhope0000\LXDClient\Model\Client\LxdClient;

class ImportLinuxContainersByAlias
{
    public function __construct(
        LxdClient $lxdClient
    ) {
        $this->client = $lxdClient;
    }

    public function import(array $hosts, array $aliases)
    {
        foreach ($hosts as $hostId) {
            $client = $this->client->getANewClient($hostId);
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
