<?php

namespace dhope0000\LXDClient\Controllers\Networks;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Networks\DeleteNetwork;
use Symfony\Component\Routing\Attribute\Route;

class DeleteNetworkController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly DeleteNetwork $deleteNetwork
    ) {
    }

    #[Route(path: '/api/Networks/DeleteNetworkController/delete', name: 'Delete Network', methods: ['POST'])]
    public function delete(Host $host, $network)
    {
        $this->deleteNetwork->delete($host, $network);
        return [
            'state' => 'success',
            'message' => 'Deleted network',
        ];
    }
}
