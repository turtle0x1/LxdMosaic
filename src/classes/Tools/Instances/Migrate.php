<?php
namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Objects\Host;

class Migrate
{
    public function migrate(
        Host $sourceHost,
        string $instance,
        Host $destinationHost,
        string $newContainerName,
        bool $delete = false
    ) {
        $this->checkIfMigratingToSameHost($sourceHost, $destinationHost);
        
        $this->checkIfHostUrlIsLocalhost($sourceHost, "source");
        $this->checkIfHostUrlIsLocalhost($destinationHost, "destination");

        $sourceHost->instances->migrate(
            $destinationHost->getClient(),
            $instance,
            $newContainerName,
            true
        );

        if ($delete) {
            $sourceHost->instances->remove($instance);
        }

        return true;
    }

    public function checkIfMigratingToSameHost(Host $source, Host $destination)
    {
        if ($source->getUrl() === $destination->getUrl()) {
            throw new \Exception("You must use two different hosts to migrate", 1);
        }
    }

    private function checkIfHostUrlIsLocalhost(Host $host, string $type)
    {
        if (parse_url($host->getUrl())["host"] == "localhost") {
            throw new \Exception("Your $type server has a URL of 'localhost', this wont work!", 1);
        }
    }
}
