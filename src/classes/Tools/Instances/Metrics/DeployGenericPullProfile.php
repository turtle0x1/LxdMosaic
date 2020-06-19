<?php

namespace dhope0000\LXDClient\Tools\Instances\Metrics;

use dhope0000\LXDClient\Objects\Host;

class DeployGenericPullProfile
{
    public function deploy(Host $host)
    {
        try {
            $host->profiles->info("lxdMosaicPullMetrics");
            return true;
        } catch (\Throwable $e) {
            return $this->deployProfile($host);
        }
    }

    private function deployProfile($host)
    {
        return $host->profiles->create("lxdMosaicPullMetrics", "Indicates LxdMosaic should pull metrics from these instances", [
            "environment.lxdMosaicPullMetrics"=>"y"
        ]);
    }
}
