<?php
namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Objects\Host;

class Migrate
{
    public function migrate(
        Host $sourceHost,
        string $instance,
        Host $destinationHost,
        string $newContainerName
    ) {
        $sourceHost->instances->migrate(
            $destinationHost->getClient(),
            $instance,
            $newContainerName,
            true
        );
        return true;
    }
}
