<?php

namespace dhope0000\LXDClient\Tools\Networks\Tools;

use dhope0000\LXDClient\Tools\Instances\GetHostsInstances;

/** @deprecated */
class FindIpAddress
{
    public function __construct(
        private readonly GetHostsInstances $getHostsInstances
    ) {
    }

    /**
     * @deprecated
     */
    public function find(string $ip)
    {
        $hostsContainers = $this->getHostsInstances->getAll();
        foreach ($hostsContainers as $host) {
            if (empty($host->getCustomProp('containers'))) {
                continue;
            }
            foreach ($host->getCustomProp('containers') as $instance => $instanceDetails) {
                if (empty($instanceDetails['state']['network'])) {
                    continue;
                }

                $network = $instanceDetails['state']['network'];

                foreach ($network as $network) {
                    foreach ($network['addresses'] as $address) {
                        if ($address['address'] == $ip) {
                            $host->removeCustomProp('containers');
                            return [
                                'container' => $instance,
                                'alias' => $host->getAlias(),
                                'hostId' => $host->getHostId(),
                            ];
                        }
                    }
                }
            }
        }
        return false;
    }
}
