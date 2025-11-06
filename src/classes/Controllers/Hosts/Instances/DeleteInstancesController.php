<?php

namespace dhope0000\LXDClient\Controllers\Hosts\Instances;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\DeleteInstances;
use Symfony\Component\Routing\Attribute\Route;

class DeleteInstancesController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly DeleteInstances $deleteInstances
    ) {
    }

    #[Route(path: '/api/Hosts/Instances/DeleteInstancesController/delete', name: 'Delete Instances', methods: ['POST'])]
    public function delete(int $userId, Host $host, array $containers)
    {
        $this->deleteInstances->delete($userId, $host, $containers);
        return [
            'state' => 'success',
            'message' => 'Delete Containers',
        ];
    }
}
