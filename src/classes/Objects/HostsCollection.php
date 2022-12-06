<?php

namespace dhope0000\LXDClient\Objects;

use dhope0000\LXDClient\Objects\Host;

class HostsCollection implements \Iterator, \JsonSerializable
{
    /**
     * @var array<\dhope0000\LXDClient\Objects\Host>
     */
    private $hosts = [];
    private $index = 0;

    /**
     * @param array<\dhope0000\LXDClient\Objects\Host> $hosts
     */
    public function __construct(array $hosts)
    {
        foreach ($hosts as $host) {
            $this->addHost($host);
        }
    }

    final public function jsonSerialize()
    {
        return $this->hosts;
    }
    /**
     * @param \dhope0000\LXDClient\Objects\Host $host
     */
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

    public function current()
    {
        return $this->hosts[$this->index];
    }

    public function next()
    {
        $this->index ++;
    }

    public function key()
    {
        return $this->index;
    }

    public function valid()
    {
        return isset($this->hosts[$this->key()]);
    }

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
