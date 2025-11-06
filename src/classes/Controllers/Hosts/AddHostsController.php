<?php

namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Tools\Hosts\AddHosts;
use Symfony\Component\Routing\Annotation\Route;

class AddHostsController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly AddHosts $addHosts
    ) {
    }

    /**
     * @Route("/api/Hosts/AddHostsController/add", name="Add Hosts", methods={"POST"})
     */
    public function add($userId, array $hostsDetails)
    {
        $this->addHosts->add($userId, $hostsDetails);
        return [
            'state' => 'success',
            'messages' => 'Added Hosts',
        ];
    }
}
