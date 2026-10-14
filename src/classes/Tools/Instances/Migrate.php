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
        $this->checkIfHostUsesSocket($sourceHost, 'source');
        $this->checkIfHostUsesSocket($destinationHost, 'destination');

        $this->checkIfHostUrlIsLocalhost($sourceHost, 'source');
        $this->checkIfHostUrlIsLocalhost($destinationHost, 'destination');

        $response = $sourceHost->instances->migrate(
            $destinationHost->getClient(),
            $instance,
            $newContainerName,
            true
        );

        if ($response['err'] !== '') {
            throw new \Exception($response['err'], 1);
        }

        if ($delete) {
            $sourceHost->instances->remove($instance);
        }

        return true;
    }

    public function checkIfMigratingToSameHost(Host $source, Host $destination)
    {
        if ($source->getUrl() === $destination->getUrl()) {
            throw new \Exception('You must use two different hosts to migrate', 1);
        }
    }

    private function checkIfHostUsesSocket(Host $host, string $type)
    {
        if ($host->usesSocket()) {
            throw new \Exception("Your {$type} server is connected via a Unix socket. Host-to-host migration is not supported over Unix sockets.", 1);
        }
    }

    private function checkIfHostUrlIsLocalhost(Host $host, string $type)
    {
        if (parse_url($host->getUrl())['host'] == 'localhost') {
            throw new \Exception("Your {$type} server has a URL of 'localhost', this wont work!", 1);
        }
    }
}
