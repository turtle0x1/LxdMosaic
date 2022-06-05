<?php
namespace dhope0000\LXDClient\Tools\Networks\Tools;

use dhope0000\LXDClient\Tools\Instances\GetHostsInstances;

class FindIpAddress
{
    private $getHostsInstances;
    
    public function __construct(
        GetHostsInstances $getHostsInstances
    ) {
        $this->getHostsInstances = $getHostsInstances;
    }

    public function find(string $ip)
    {
        $hostsContainers = $this->getHostsInstances->getAll();
        foreach ($hostsContainers as $host) {
            if (empty($host->getCustomProp("containers"))) {
                continue;
            }
            foreach ($host->getCustomProp("containers") as $instance => $instanceDetails) {
                if (empty($instanceDetails["state"]["network"])) {
                    continue;
                }

                $network = $instanceDetails["state"]["network"];

                foreach ($network as $network) {
                    foreach ($network["addresses"] as $address) {
                        if ($address["address"] == $ip) {
                            $host->removeCustomProp("containers");
                            return [
                                "container"=>$instance,
                                "alias"=>$host->getAlias(),
                                "hostId"=>$host->getHostId()
                            ];
                        }
                    }
                }
            }
        }
        return false;
    }
}
