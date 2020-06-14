<?php

namespace dhope0000\LXDClient\Controllers\Instances\Files;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Files\GetPath;

class GetPathController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $getPath;

    public function __construct(GetPath $getPath)
    {
        $this->getPath = $getPath;
    }

    public function get(
        Host $host,
        string $container,
        string $path,
        $download
    ) {
        return $this->getPath->get($host, $container, $path, (int) $download);
    }
}
