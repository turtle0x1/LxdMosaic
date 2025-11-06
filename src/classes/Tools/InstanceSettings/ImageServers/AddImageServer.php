<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings\ImageServers;

use dhope0000\LXDClient\Model\InstanceSettings\ImageServers\InsertImageServer;
use dhope0000\LXDClient\Tools\Images\SearchRemoteImages;

class AddImageServer
{
    public function __construct(
        private readonly SearchRemoteImages $searchRemoteImages,
        private readonly InsertImageServer $insertImageServer
    ) {
    }

    public function add(string $alias, string $url, int $protocol)
    {
        $serverContents = $this->searchRemoteImages->getSimpleStreamsJson($url);
        if (!isset($serverContents['products'])) {
            throw new \Exception("simplestreams server didn't respond with expected 'product' key");
        }
        $this->insertImageServer->insert($alias, $url, $protocol);
    }
}
