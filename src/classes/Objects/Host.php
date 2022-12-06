<?php

namespace dhope0000\LXDClient\Objects;

use dhope0000\LXDClient\Model\Client\LxdClient;
use Opensaucesystems\Lxd\Client;

/**
 * @property-read \Opensaucesystems\Lxd\Endpoint\Storage $storage
 * @property-read \Opensaucesystems\Lxd\Endpoint\Images $images
 * @property-read \Opensaucesystems\Lxd\Endpoint\Projects $projects
 * @property-read \Opensaucesystems\Lxd\Endpoint\Profiles $profiles
 * @property-read \Opensaucesystems\Lxd\Endpoint\InstaceBase $instances.
 * @property-read \Opensaucesystems\Lxd\Endpoint\Networks $networks.
 * @property-read \Opensaucesystems\Lxd\Endpoint\Volumes $volumes.
 * @property-read \Opensaucesystems\Lxd\Endpoint\Resources $resources.
 * @property-read \Opensaucesystems\Lxd\Endpoint\Host $host.
 * @property-read \Opensaucesystems\Lxd\Endpoint\Certificates $certificates..
 * @property-read \Opensaucesystems\Lxd\Endpoint\Warnings $warnings..
 */
class Host implements \JsonSerializable
{
    private ?int $id;
    private ?string $urlAndPort;
    private ?string $certPath;
    private ?string $alias;
    private ?string $certFilePath;
    private ?string $keyFilePath ;
    private ?int $hostOnline;
    private ?string $socketPath;
    private ?int $supportsLoadAvgs;
    private array $customProps = [];

    private ?LxdClient $lxdClient;
    private ?Client $client = null;

    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function setCustomProp(string $name, $value) :void
    {
        if (in_array($name, ["hostId", "alias", "urlAndPort"])) {
            throw new \Exception("Not to set $name is custom props", 1);
        }
        $this->customProps[$name] = $value;
    }

    public function getCustomProp(string $name) :array
    {
        return $this->customProps[$name];
    }

    public function removeCustomProp(string $name) :void
    {
        unset($this->customProps[$name]);
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

    public function getSocketPath()
    {
        return $this->socketPath;
    }

    public function hostSupportLoadAvgs() :bool
    {
        return (bool) $this->supportsLoadAvgs;
    }

    public function callClientMethod(string $method, $param = null)
    {
        if ($param !== null) {
            return $this->getClient()->$method($param);
        } else {
            return $this->getClient()->$method();
        }
    }

    final public function jsonSerialize() :array
    {
        return array_merge([
            "hostId"=>$this->id,
            "alias"=>$this->alias,
            "urlAndPort"=>$this->urlAndPort,
            "hostOnline"=>$this->hostOnline,
            "supportsLoadAvgs"=>$this->supportsLoadAvgs,
            "currentProject"=>$this->getProject(),
            "socketPath"=>$this->socketPath
        ], $this->customProps);
    }
    /**
     * You should probably not be using this.
     */
    public function getClient() :Client
    {
        if ($this->client == null) {
            $this->client = $this->lxdClient->getClientWithHost($this);
        }
        return $this->client;
    }

    public function setProject(string $project) :void
    {
        if ($this->client == null) {
            $this->client = $this->lxdClient->getClientWithHost($this);
        }
        $this->client->setProject($project);
    }

    public function getProject() :string
    {
        if ($this->client == null) {
            $this->client = $this->lxdClient->getClientWithHost($this);
        }
        return $this->client->getProject();
    }

    public function __set(string $prop, $value)
    {
        throw new \Exception("Not allowed to set public properties on this object, use get/set/removeCustomProp", 1);
    }

    public function __get(string $target)
    {
        return $this->getClient()->$target;
    }
}
