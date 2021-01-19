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
        $this->hostUrlIsLocalhostCheck($sourceHost, "source");
        $this->hostUrlIsLocalhostCheck($destinationHost, "destination");

        $sourceHost->instances->migrate(
            $destinationHost->getClient(),
            $instance,
            $newContainerName,
            true
        );
        return true;
    }

    private function hostUrlIsLocalhostCheck(Host $host, string $type)
    {
        if (parse_url($host->getUrl())["host"] == "localhost") {
            throw new \Exception("Your $type server has a URL of 'localhost', this wont work!", 1);
        }
    }
}
