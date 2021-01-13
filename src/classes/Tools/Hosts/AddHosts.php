<?php

namespace dhope0000\LXDClient\Tools\Hosts;

use dhope0000\LXDClient\Model\Hosts\AddHost as AddHostModel;
use dhope0000\LXDClient\Tools\Hosts\GenerateCert;
use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;

class AddHosts
{
    public function __construct(
        AddHostModel $addHost,
        GenerateCert $generateCert,
        GetDetails $getDetails,
        LxdClient $lxdClient,
        FetchUserDetails $fetchUserDetails
    ) {
        $this->generateCert = $generateCert;
        $this->addHost = $addHost;
        $this->getDetails = $getDetails;
        $this->lxdClient = $lxdClient;
        $this->fetchUserDetails = $fetchUserDetails;
    }

    public function add($userId, array $hostsDetails)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId) === "1";

        if (!$isAdmin) {
            throw new \Exception("Not allowed to add hosts", 1);
        }

        foreach ($hostsDetails as $hostsDetail) {
            $this->validateDetails($hostsDetail);

            $hostName = $this->addSchemeAndDefaultPort($hostsDetail["name"]);

            if (!empty($this->getDetails->fetchHostByUrl($hostName))) {
                continue;
            }

            try {
                $result = $this->generateCert->createCertAndPushToServer(
                    $hostName,
                    $hostsDetail["trustPassword"]
                );

                $alias = null;

                if (isset($hostsDetail["alias"]) && !empty($hostsDetail["alias"])) {
                    $alias = $hostsDetail["alias"];
                }

                $config = $this->lxdClient->createConfigArray(realpath($_ENV["LXD_CERTS_DIR"] . "/" . $result["shortPaths"]["combined"]));

                $client = $this->lxdClient->createNewClient($hostName, $config);

                $clusterInfo = $client->cluster->info();
                $inCluster = $clusterInfo["enabled"];

                if ($inCluster) {
                    $alias = $clusterInfo["server_name"];
                }

                $this->addHost->addHost(
                    $hostName,
                    $result["shortPaths"]["key"],
                    $result["shortPaths"]["cert"],
                    $result["shortPaths"]["combined"],
                    $alias
                );

                if ($inCluster) {
                    //TODO Recursion ?
                    $members = $client->cluster->members->all();
                    $extraMembersToAdd = [];
                    foreach ($members as $member) {
                        $inf = $client->cluster->members->info($member);
                        if ($hostName == $inf["url"]) {
                            continue;
                        }
                        $extraMembersToAdd[] = [
                            "name"=>$inf["url"],
                            "trustPassword"=>$hostsDetail["trustPassword"],
                            "alias"=>$member
                        ];
                    }
                    $this->add($userId, $extraMembersToAdd);
                }
            } catch (\Http\Client\Exception\NetworkException $e) {
                throw new \Exception("Can't connect to ". $hostsDetail['name'] . ", is lxd running and the port open?", 1);
            }
        }

        return true;
    }

    private function addSchemeAndDefaultPort($name)
    {
        $parts = parse_url($name);

        if (!isset($parts["scheme"])) {
            $parts["scheme"] = "https://";
        } elseif ($parts["scheme"] == "https") {
            $parts["scheme"] = "https://";
        }

        if (!isset($parts["port"])) {
            $parts["port"] = 8443;
        }

        $path = isset($parts["path"]) ? $parts["path"] : $parts["host"];

        return $parts["scheme"] . $path . ":" . $parts["port"];
    }

    private function validateDetails($host)
    {
        if (!isset($host["name"]) || empty($host["name"])) {
            throw new \Exception("Please provide name", 1);
        } elseif (!isset($host["trustPassword"]) || empty($host["trustPassword"])) {
            throw new \Exception("Please provide trust password", 1);
        }
        return true;
    }
}
