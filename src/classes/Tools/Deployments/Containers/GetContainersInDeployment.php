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
                    if (strpos($item, "/1.0/containers") !== false || strpos($item, "/1.0/instances") !== false) {
                        $revId = $profile["revId"];

                        if (isset($revDetails[$revId])) {
                            $revInfo = $revDetails[$revId];
                        } else {
                            $revInfo = $this->getConfig->getCloudConfigByRevId($revId);
                            $revDetails[$revId] = $revInfo;
                        }

                        $containerName = str_replace("/1.0/containers/", "", $item);
                        $containerName = str_replace("/1.0/instances/", "", $item);

                        if (count(explode("/", $containerName)) > 1) {
                            continue;
                        }

                        $containerName = preg_replace("/\?project=.*/", "", $containerName);

                        $info = $client->instances->info($containerName);
                        if (!isset($output[$host])) {
                            $output[$host] = [
                                "hostId"=>$data["hostId"],
                                "containers"=>[],
                                "hostInfo"=>$client->host->info()
                            ];
                        }

                        if ($info["location"] !== "none") {
                            if ($output[$host]["hostInfo"]["environment"]["server_name"] !== $info["location"]) {
                                continue;
                            }
                        }

                        $state = $client->instances->state($containerName);
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
