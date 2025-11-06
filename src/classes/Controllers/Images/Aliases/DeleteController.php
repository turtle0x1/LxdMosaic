<?php

namespace dhope0000\LXDClient\Controllers\Images\Aliases;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Images\Aliases\DeleteAlias;
use Symfony\Component\Routing\Attribute\Route;

class DeleteController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly DeleteAlias $deleteAlias
    ) {
    }

    #[Route(path: '/api/Images/Aliases/DeleteController/delete', name: 'Delete Image Alias', methods: ['POST'])]
    public function delete(Host $host, string $name)
    {
        $lxdResponse = $this->deleteAlias->delete($host, $name);
        return [
            'state' => 'success',
            'message' => 'Deleted alias',
            'lxdResponse' => $lxdResponse,
        ];
    }
}
