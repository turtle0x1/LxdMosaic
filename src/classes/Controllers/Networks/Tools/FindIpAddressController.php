<?php

namespace dhope0000\LXDClient\Controllers\Networks\Tools;

use dhope0000\LXDClient\Tools\Networks\Tools\FindIpAddress;
use Symfony\Component\Routing\Annotation\Route;

class FindIpAddressController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $findIpAddress;
    
    public function __construct(FindIpAddress $findIpAddress)
    {
        $this->findIpAddress = $findIpAddress;
    }
    /**
     * @Route("", name="Find instance ip address")
     */
    public function find(string $ip)
    {
        $result = $this->findIpAddress->find($ip);
        return ["state"=>"success", "result"=>$result];
    }
}
