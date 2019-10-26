<?php

namespace dhope0000\LXDClient\Controllers\Containers\Files;

use dhope0000\LXDClient\Tools\Containers\Files\GetPath;

class GetPathController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $getPath;

    public function __construct(GetPath $getPath)
    {
        $this->getPath = $getPath;
    }

    public function get(
        int $hostId,
        string $container,
        string $path,
        $download
    ) {
        return $this->getPath->get($hostId, $container, $path, (int) $download);
    }
}
