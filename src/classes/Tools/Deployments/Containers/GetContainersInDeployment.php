<?php

namespace dhope0000\LXDClient\Tools\Deployments\Containers;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\CloudConfig\GetConfig;

class GetContainersInDeployment
{
    public function __construct(LxdClient $lxdClient, GetConfig $getConfig)
    {
        $this->client = $lxdClient;
        $this->getConfig = $getConfig;
    }

    public function getFromProfile(array $profilesInDeployment)
    {
        $output = [];
        $revDetails = [];
        foreach ($profilesInDeployment as $host => $data) {
            $client = $this->client->getANewClient($data["hostId"]);
            foreach ($data["profiles"] as $profile) {
                foreach ($profile["usedBy"] as $item) {
                    if (strpos($item, "/1.0/containers") !== false) {
                        $revId = $profile["revId"];

                        if (isset($revDetails[$revId])) {
                            $revInfo = $revDetails[$revId];
                        } else {
                            $revInfo = $this->getConfig->getCloudConfigByRevId($revId);
                            $revDetails[$revId] = $revInfo;
                        }

                        $containerName = str_replace("/1.0/containers/", "", $item);
                        $info = $client->containers->info($containerName);
                        if (!isset($output[$host])) {
                            $output[$host] = [
                                "hostId"=>$data["hostId"],
                                "containers"=>[]
                            ];
                        }

                        $state = $client->containers->state($containerName);
                        $output[$host]["containers"][] = [
                            "createdAt"=>$info["created_at"],
                            "statusCode"=>$info["status_code"],
                            "name"=>$info["name"],
                            "type"=>$revInfo["name"],
                            "state"=>[
                                "network"=>$state["network"],
                                "memory"=>$state["memory"]
                            ]
                        ];
                    }
                }
            }
        }
        return $output;
    }
}
