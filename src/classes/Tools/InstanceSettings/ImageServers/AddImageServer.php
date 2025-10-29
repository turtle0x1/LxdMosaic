<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings\ImageServers;

use dhope0000\LXDClient\Tools\Images\SearchRemoteImages;
use dhope0000\LXDClient\Model\InstanceSettings\ImageServers\InsertImageServer;

class AddImageServer
{
    private $searchRemoteImages;
    private $insertImageServer;

    public function __construct(
        SearchRemoteImages $searchRemoteImages,
        InsertImageServer $insertImageServer
    ) {
        $this->searchRemoteImages = $searchRemoteImages;
        $this->insertImageServer = $insertImageServer;
    }

    public function add(string $alias, string $url, int $protocol)
    {
        $serverContents = $this->searchRemoteImages->getSimpleStreamsJson($url);
        if (!isset($serverContents["products"])) {
            throw new \Exception("simplestreams server didn't respond with expected 'product' key");
        }
        $this->insertImageServer->insert($alias, $url, $protocol);
    }
}
