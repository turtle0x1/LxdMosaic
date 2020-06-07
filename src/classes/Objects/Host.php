<?php

namespace dhope0000\LXDClient\Objects;

use dhope0000\LXDClient\Model\Client\LxdClient;

class Host
{
    private $id;
    private $urlAndPort;
    private $certPath;
    private $alias;
    private $client = null;

    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function getHostId() :int
    {
        return $this->id;
    }

    public function getCertPath() :string
    {
        return $this->certPath;
    }

    public function getUrl() :string
    {
        return $this->urlAndPort;
    }

    public function hostOnline() :bool
    {
        return (bool) $this->hostOnline;
    }

    public function getHostAlias() :string
    {
        return $this->alias;
    }

    public function __get($target)
    {
        if ($this->client == null) {
            $this->client = $this->lxdClient->getClientWithHost($this);
        }
        return $this->client->$target;
    }
}
