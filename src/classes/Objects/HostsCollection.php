<?php

namespace dhope0000\LXDClient\Objects;

use dhope0000\LXDClient\Objects\Host;

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

    final public function jsonSerialize()
    {
        return $this->hosts;
    }

    public function addHost(Host $host)
    {
        $this->hosts[] = $host;
    }

    public function getAllHosts()
    {
        return $this->hosts();
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
