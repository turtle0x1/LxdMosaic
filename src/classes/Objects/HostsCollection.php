<?php

namespace dhope0000\LXDClient\Objects;

class HostsCollection implements \Iterator, \JsonSerializable
{
    private $hosts = [];

    private $index = 0;

    public function __construct(array $hosts)
    {
        foreach ($hosts as $host) {
            $this->addHost($host);
        }
    }

    #[\Override]
    final public function jsonSerialize()
    {
        return $this->hosts;
    }

    public function addHost(Host $host)
    {
        $this->hosts[] = $host;
    }

    public function removeHostId(int $hostId)
    {
        foreach ($this->hosts as $index => $host) {
            if ($host->getHostId() == $hostId) {
                unset($this->hosts[$index]);
                break;
            }
        }
    }

    public function getAllHosts()
    {
        return $this->hosts;
    }

    #[\Override]
    public function current()
    {
        return $this->hosts[$this->index];
    }

    #[\Override]
    public function next()
    {
        $this->index ++;
    }

    #[\Override]
    public function key()
    {
        return $this->index;
    }

    #[\Override]
    public function valid()
    {
        return isset($this->hosts[$this->key()]);
    }

    #[\Override]
    public function rewind()
    {
        $this->index = 0;
    }

    public function reverse()
    {
        $this->hosts = array_reverse($this->hosts);
        $this->rewind();
    }
}
