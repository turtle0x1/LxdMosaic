<?php

namespace dhope0000\LXDClient\Tools\Instances\Containers;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Constants\LxdInstanceTypes;
use dhope0000\LXDClient\Tools\Containers\CreateContainer;
use dhope0000\LXDClient\Tools\Instances\Metrics\GetMetricVendorData;

class CreateMetricContainer
{
    public function __construct(
        LxdClient $lxdClient,
        CreateContainer $createContainer,
        GetMetricVendorData $getMetricVendorData
    ) {
        $this->lxdClient = $lxdClient;
        $this->createContainer = $createContainer;
        $this->getMetricVendorData = $getMetricVendorData;
    }

    public function create(string $name, array $hostIds)
    {
        $config = [];
        $config["user.vendor-data"] = $this->getMetricVendorData->get();
        $config["environment.lxdMosaicMetrics"] = "y";

        $profileName = "metricsTestOne";
        $description = "Created by LXDMosaic";

        foreach ($hostIds as $hostId) {
            $client = $this->lxdClient->getANewClient($hostId);

            $client->profiles->create(
                $profileName,
                $description,
                $config
            );
        }

        $imageDetails = [
            "fingerprint"=>"9e7158fc0683",
        ];

        $this->createContainer->create(
            LxdInstanceTypes::CONTAINER,
            $name,
            [$profileName, "default"],
            $hostIds,
            $imageDetails
        );

        return true;
    }
}
