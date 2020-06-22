<?php
namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Tools\Hosts\AddHosts;
use Symfony\Component\Routing\Annotation\Route;

class AddHostsController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(AddHosts $addHosts)
    {
        $this->addHosts = $addHosts;
    }
    /**
     * @Route("", name="Add Hosts")
     */
    public function add(array $hostsDetails)
    {
        $this->addHosts->add($hostsDetails);
        return ["state"=>"success", "messages"=>"Added Hosts"];
    }
}
