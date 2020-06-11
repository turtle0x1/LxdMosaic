<?php

namespace dhope0000\LXDClient\Tools\Hosts;

use dhope0000\LXDClient\Model\Hosts\AddHost as AddHostModel;
use dhope0000\LXDClient\Tools\Hosts\GenerateCert;
use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Tools\Node\Hosts;

class AddHosts
{
    public function __construct(
        AddHostModel $addHost,
        GenerateCert $generateCert,
        GetDetails $getDetails,
        Hosts $hosts
    ) {
        $this->generateCert = $generateCert;
        $this->addHost = $addHost;
        $this->getDetails = $getDetails;
        $this->hosts = $hosts;
    }

    public function add(array $hostsDetails)
    {
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

                $this->addHost->addHost(
                    $hostName,
                    $result["shortPaths"]["key"],
                    $result["shortPaths"]["cert"],
                    $result["shortPaths"]["combined"],
                    $alias
                );

                $hostId = $this->addHost->getId();

                $host = $this->getDetails->fetchHost($hostId);

                if ($host->cluster->info()["enabled"]) {
                    //TODO Recursion ?
                    $members = $host->cluster->members->all();
                    $extraMembersToAdd = [];
                    foreach ($members as $member) {
                        $inf = $host->cluster->members->info($member);
                        if ($hostName == $inf["url"]) {
                            continue;
                        }
                        $extraMembersToAdd[] = [
                            "name"=>$inf["url"],
                            "trustPassword"=>$hostsDetail["trustPassword"],
                            "alias"=>$member
                        ];
                    }
                    $this->add($extraMembersToAdd);
                }
            } catch (\Http\Client\Exception\NetworkException $e) {
                throw new \Exception("Can't connect to ". $hostsDetail['name'] . ", is lxd running and the port open?", 1);
            }
        }

        $this->hosts->reloadHosts();

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
