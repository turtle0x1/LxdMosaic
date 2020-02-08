<?php

namespace dhope0000\LXDClient\Tools\Instances\Metrics;

use dhope0000\LXDClient\Model\Client\LxdClient;

class DeployGenericPullProfile
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function deploy(int $hostId)
    {
        $client = $this->lxdClient->getANewClient($hostId);

        try {
            $client->profiles->info("lxdMosaicPullMetrics");
            return true;
        } catch (\Throwable $e) {
            return $this->deployProfile($client);
        }
    }

    private function deployProfile($client)
    {
        return $client->profiles->create("lxdMosaicPullMetrics", "Indicates LxdMosaic should pull metrics from these instances", [
            "environment.lxdMosaicPullMetrics"=>"y"
        ]);
    }
}
