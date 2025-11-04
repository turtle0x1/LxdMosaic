<?php

namespace dhope0000\LXDClient\Controllers\Networks\Tools;

use dhope0000\LXDClient\Tools\Networks\Tools\FindIpAddress;
use Symfony\Component\Routing\Annotation\Route;

/** @deprecated */
class FindIpAddressController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $findIpAddress;
    
    public function __construct(FindIpAddress $findIpAddress)
    {
        $this->findIpAddress = $findIpAddress;
    }
    /**
     * @Route("/api/Networks/Tools/FindIpAddressController/find", name="Find instance ip address", methods={"POST"})
     * @deprecated
     */
    public function find(string $ip)
    {
        $result = $this->findIpAddress->find($ip);
        return ["state"=>"success", "result"=>$result];
    }
}
