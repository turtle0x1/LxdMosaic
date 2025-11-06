<?php

namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Tools\Hosts\RemoveHost;
use DI\Container;
use Symfony\Component\Routing\Attribute\Route;

class DeleteHostController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly RemoveHost $removeHost,
        private readonly Container $container
    ) {
    }

    #[Route(path: '/api/Hosts/DeleteHostController/delete', name: 'Delete Host', methods: ['POST'])]
    public function delete(int $userId, int $hostId)
    {
        $this->container->call(["dhope0000\LXDClient\Model\Database\Database", 'beginTransaction']);
        $this->removeHost->remove($userId, $hostId);
        $this->container->call(["dhope0000\LXDClient\Model\Database\Database", 'commitTransaction']);
        return [
            'state' => 'success',
            'message' => 'Deleted host',
        ];
    }
}
