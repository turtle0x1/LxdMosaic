<?php

namespace dhope0000\LXDClient\Controllers\Images\Aliases;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Images\Aliases\RenameAlias;
use Symfony\Component\Routing\Attribute\Route;

class RenameController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly RenameAlias $renameAlias
    ) {
    }

    #[Route(path: '/api/Images/Aliases/RenameController/rename', name: 'Rename Image Alias', methods: ['POST'])]
    public function rename(Host $host, string $name, string $newName)
    {
        $lxdResponse = $this->renameAlias->rename($host, $name, $newName);
        return [
            'state' => 'success',
            'message' => 'Renamed alias',
            'lxdResponse' => $lxdResponse,
        ];
    }
}
