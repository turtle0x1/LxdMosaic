<?php

namespace dhope0000\LXDClient\Controllers\Networks\Tools;

use dhope0000\LXDClient\Tools\Networks\Tools\FindIpAddress;
use Symfony\Component\Routing\Annotation\Route;

/** @deprecated */
class FindIpAddressController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly FindIpAddress $findIpAddress
    ) {
    }

    /**
     * @Route("/api/Networks/Tools/FindIpAddressController/find", name="Find instance ip address", methods={"POST"})
     * @deprecated
     */
    public function find(string $ip)
    {
        $result = $this->findIpAddress->find($ip);
        return [
            'state' => 'success',
            'result' => $result,
        ];
    }
}
