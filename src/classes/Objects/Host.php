<?php

namespace dhope0000\LXDClient\Objects;

use dhope0000\LXDClient\Model\Client\LxdClient;
use Opensaucesystems\Lxd\Client;

class Host implements \JsonSerializable
{
    private $id;

    private $urlAndPort;

    private $certPath;

    private $alias;

    private $certFilePath;

    private $keyFilePath;

    private $hostOnline;

    private $supportsLoadAvgs;

    private $customProps = [];

    private $client = null;

    public function __construct(
        private readonly LxdClient $lxdClient
    ) {
    }

    public function __set($prop, $value)
    {
        throw new \Exception('Not allowed to set public properties on this object, use get/set/removeCustomProp', 1);
    }

    public function __get($target)
    {
        return $this->getClient()
            ->{$target};
    }

    public function setCustomProp(string $name, $value)
    {
        if (in_array($name, ['hostId', 'alias', 'urlAndPort'])) {
            throw new \Exception("Not to set {$name} is custom props", 1);
        }
        $this->customProps[$name] = $value;
    }

    public function getCustomProp(string $name)
    {
        return $this->customProps[$name];
    }

    public function removeCustomProp(string $name)
    {
        unset($this->customProps[$name]);
    }

    public function getHostId(): int
    {
        return $this->id;
    }

    public function getCertPath(): string
    {
        return $this->certPath;
    }

    public function getUrl(): string
    {
        return $this->urlAndPort;
    }

    public function hostOnline(): bool
    {
        return (bool) $this->hostOnline;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function hostSupportLoadAvgs(): bool
    {
        return (bool) $this->supportsLoadAvgs;
    }

    public function callClientMethod($method, $param = null)
    {
        if ($param !== null) {
            return $this->getClient()
                ->{$method}($param);
        }
        return $this->getClient()
            ->{$method}();

    }

    #[\Override]
    final public function jsonSerialize()
    {
        return array_merge([
            'hostId' => $this->id,
            'alias' => $this->alias,
            'urlAndPort' => $this->urlAndPort,
            'hostOnline' => $this->hostOnline,
            'supportsLoadAvgs' => $this->supportsLoadAvgs,
            'currentProject' => $this->getProject(),
        ], $this->customProps);
    }

    /**
     * You should probably not be using this.
     */
    public function getClient(): Client
    {
        if ($this->client == null) {
            $this->client = $this->lxdClient->getClientWithHost($this);
        }
        return $this->client;
    }

    public function setProject(string $project)
    {
        if ($this->client == null) {
            $this->client = $this->lxdClient->getClientWithHost($this);
        }
        $this->client->setProject($project);
    }

    public function getProject()
    {
        if ($this->client == null) {
            $this->client = $this->lxdClient->getClientWithHost($this);
        }
        return $this->client->getProject();
    }
}
