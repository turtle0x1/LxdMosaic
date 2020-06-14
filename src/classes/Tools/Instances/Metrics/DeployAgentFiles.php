<?php

namespace dhope0000\LXDClient\Tools\Instances\Metrics;

use dhope0000\LXDClient\Objects\Host;

class DeployAgentFiles
{
    public function deploy(Host $host, string $container)
    {
        $host->instances->files->write($container, "/etc/lxdMosaic", "", null, null, null, "directory");

        $host->instances->files->write($container, "/etc/lxdMosaic/metrics.py", file_get_contents("/var/www/LxdMosaic/python/sysMetrics/main.py"));

        $host->instances->files->write($container, "/etc/cron.d/lxdMosaicMetrics", "*/1 * * * * root python3 /etc/lxdMosaic/metrics.py
        ");

        return true;
    }
}
