<?php
namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Migrate;

class Copy
{
    public function __construct(Migrate $migrate)
    {
        $this->migrate = $migrate;
    }

    public function copy(
        Host $host,
        string $instance,
        string $newInstance,
        Host $destination
    ) {
        if ($host->getHostId() !== $destination->getHostId()) {
            return $this->migrate->migrate(
                $host,
                $instance,
                $destination,
                $newInstance
            );
        }

        $r = $host->instances->copy($instance, $newInstance, [], true);
        // There is some error that is not being caught here so added this checking
        if (isset($r["err"]) && !empty($r["err"])) {
            throw new \Exception($r["err"], 1);
        }
        return $r;
    }
}
