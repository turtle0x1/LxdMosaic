<?php

namespace dhope0000\LXDClient\Controllers\Hosts\Warnings;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Hosts\Warnings\DeleteWarning;
use Symfony\Component\Routing\Attribute\Route;

class DeleteWarningController
{
    public function __construct(
        private readonly DeleteWarning $deleteWarning
    ) {
    }

    #[Route(path: '/api/Hosts/Warnings/DeleteWarningController/delete', name: 'api_hosts_warnings_deletewarningcontroller_delete', methods: ['POST'])]
    public function delete(int $userId, Host $host, string $id)
    {
        $this->deleteWarning->delete($userId, $host, $id);
        return [
            'state' => 'success',
            'message' => 'Deleted warning',
            'id' => $id,
        ];
    }
}
