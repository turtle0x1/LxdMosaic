<?php

namespace dhope0000\LXDClient\Tools\Images;

use dhope0000\LXDClient\Objects\HostsCollection;

class ImportLinuxContainersByAlias
{
    public function import(HostsCollection $hosts, array $aliases)
    {
        foreach ($hosts as $host) {
            foreach ($aliases as $alias) {
                $output[] = $host->images->createFromRemote(
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
