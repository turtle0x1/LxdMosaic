<?php

namespace dhope0000\LXDClient\Tools\Instances\Metrics;

use dhope0000\LXDClient\Model\Client\LxdClient;

class DeployAgentFiles
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function deploy(int $hostId, string $container)
    {
        $client = $this->lxdClient->getANewClient($hostId);

        $client->instances->files->write($container, "/etc/lxdMosaic", "", null, null, null, "directory");

        $client->instances->files->write($container, "/etc/lxdMosaic/metrics.py", file_get_contents("/var/www/LxdMosaic/python/sysMetrics/main.py"));

        $client->instances->files->write($container, "/etc/cron.d/lxdMosaicMetrics", "*/1 * * * * root python3 /etc/lxdMosaic/metrics.py
        ");

        return true;
    }

}
