<?php

namespace dhope0000\LXDClient\Objects;

use dhope0000\LXDClient\Model\Client\LxdClient;

class Host implements \JsonSerializable
{
    private $id;
    private $urlAndPort;
    private $certPath;
    private $alias;

    private $certFilePath;
    private $keyFilePath ;
    private $hostOnline;
    private $lxdClient;
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

    public function getAlias() :string
    {
        return $this->alias;
    }

    public function callClientMethod($method, $param = null)
    {
        if ($this->client == null) {
            $this->client = $this->lxdClient->getClientWithHost($this);
        }

        if ($param !== null) {
            return $this->client->$method($param);
        } else {
            return $this->client->$method();
        }
    }

    final public function jsonSerialize()
    {
        return [
            "hostId"=>$this->id,
            "alias"=>$this->alias,
            "urlAndPort"=>$this->urlAndPort,
        ];
    }

    public function __get($target)
    {
        if ($this->client == null) {
            $this->client = $this->lxdClient->getClientWithHost($this);
        }
        return $this->client->$target;
    }
}
