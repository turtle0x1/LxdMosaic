<?php
namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Tools\Instances\Migrate;

class Copy
{
    public function __construct(LxdClient $lxdClient, Migrate $migrate)
    {
        $this->lxdClient = $lxdClient;
        $this->migrate = $migrate;
    }

    public function copy(
        int $hostId,
        string $instance,
        string $newInstance,
        int $newHostId
    ) {
        if ($hostId !== $newHostId) {
            return $this->migrate->migrate(
                $hostId,
                $instance,
                $newHostId,
                $newInstance
            );
        }
        $client = $this->lxdClient->getANewClient($hostId);
        $r = $client->instances->copy($instance, $newInstance, [], true);
        // There is some error that is not being caught here so added this checking
        if (isset($r["err"]) && !empty($r["err"])) {
            throw new \Exception($r["err"], 1);
        }
        return $r;
    }
}
