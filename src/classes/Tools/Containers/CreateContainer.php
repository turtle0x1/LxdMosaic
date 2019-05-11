<?php

namespace dhope0000\LXDClient\Tools\Containers;

use dhope0000\LXDClient\Tools\Hosts\HostsHaveContainer;
use dhope0000\LXDClient\Tools\Hosts\Images\ImportImageIfNotHave;
use dhope0000\LXDClient\Model\Client\LxdClient;

class CreateContainer
{
    public function __construct(
        LxdClient $lxdClient,
        HostsHaveContainer $hostsHaveContainer,
        ImportImageIfNotHave $importImageIfNotHave
    ) {
        $this->client = $lxdClient;
        $this->hostsHaveContainer = $hostsHaveContainer;
        $this->importImageIfNotHave = $importImageIfNotHave;
    }
    /**
     * TODO Combine the two profiles array
     * TODO Find out the $server param and send it to space
     */
    public function create(
        string $name,
        array $profiles,
        array $hosts,
        array $imageDetails,
        $server = "",
        array $profileNames = [],
        string $instanceType = "",
        array $gpus = null,
        array $config = []
    ) {
        $this->hostsHaveContainer->ifHostInListHasContainerNameThrow($hosts, $name);

        $profiles = $this->createProfileNameArray($profiles, $profileNames);

        $options = $this->createOptionsArray(
            $profiles,
            $imageDetails,
            $server,
            $instanceType,
            $gpus,
            $config
        );

        $results = [];

        foreach ($hosts as $host) {
            $client = $this->client->getANewClient($host);
            $this->importImageIfNotHave->importIfNot($client, $imageDetails);

            $response = $client->containers->create($name, $options, true);

            if ($response["status_code"] == 400) {
                throw new \Exception("Host: $host " . $response["err"], 1);
            }

            $results[] = $response;
        }

        return $results;
    }


    private function createOptionsArray(
        $profiles,
        $imageDetails,
        $server = "",
        $instanceType = "",
        array $gpus = null,
        array $config = []
    ) {
        $x = [
            "fingerprint"=>$imageDetails["fingerprint"],
            "profiles"=>$profiles,
            "server"=>$server,
            "instance_type"=>$instanceType
        ];

        foreach ($gpus as $index => $id) {
            $x["devices"] = [];
            $x["devices"]["gpu_$index"] = [
                "type"=>"gpu",
                "id"=>$id
            ];
        }

        if(!empty($config)){
            $x["config"] = $config;
        }

        return $x;
    }

    private function createProfileNameArray($profiles, $additionalProfiles)
    {
        return array_merge($profiles, $additionalProfiles);
    }
}
