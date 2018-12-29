<?php

namespace dhope0000\LXDClient\Tools\Containers;

use dhope0000\LXDClient\Model\Hosts\HostsHaveContainer;
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

    public function create(
        string $name,
        array $profiles,
        array $hosts,
        array $imageDetails,
        $server = "",
        array $profileNames = []
    ) {
        $this->hostsHaveContainer->ifHostInListHasContainerNameThrow($hosts, $name);

        $profiles = $this->createProfileNameArray($profiles, $profileNames);

        $options = $this->createOptionsArray($profiles, $imageDetails, $server);

        $results = [];

        foreach ($hosts as $host) {
            $client = $this->client->getClientByUrl($host);

            $this->importImageIfNotHave->importIfNot($client, $imageDetails);

            $response = $client->containers->create($name, $options, true);

            if ($response["status_code"] == 400) {
                throw new \Exception("Host: $host " . $response["err"], 1);
            }

            $results[] = $response;
        }

        return $results;
    }


    private function createOptionsArray($profiles, $imageDetails, $server = "")
    {
        return [
            "fingerprint"=>$imageDetails["fingerprint"],
            "profiles"=>$profiles,
            "server"=>$server
        ];
    }

    private function createProfileNameArray($profiles, $additionalProfiles)
    {
        return array_merge($profiles, $additionalProfiles);
    }
}
