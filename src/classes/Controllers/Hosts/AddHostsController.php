<?php
namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Tools\Hosts\AddHosts;

class AddHostsController
{
    public function __construct(AddHosts $addHosts)
    {
        $this->addHosts = $addHosts;
    }

    public function add(array $hostsDetails)
    {
        $this->addHosts->add($hostsDetails);
        return ["state"=>"success", "messages"=>"Added Hosts"];
    }
}
