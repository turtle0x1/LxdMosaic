<?php

namespace dhope0000\LXDClient\Controllers\Instances\Files;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Files\DeletePath;
use Symfony\Component\Routing\Attribute\Route;

class DeletePathController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly DeletePath $deletePath
    ) {
    }

    #[Route(path: '/api/Instances/Files/DeletePathController/delete', name: 'Delete Instance File', methods: ['POST'])]
    public function delete(Host $host, string $container, string $path)
    {
        $response = $this->deletePath->delete($host, $container, $path);

        return [
            'state' => 'success',
            'message' => 'Deleted item',
            'lxdResponse' => $response,
        ];
    }
}
