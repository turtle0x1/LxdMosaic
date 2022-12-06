<?php

namespace dhope0000\LXDClient\Controllers\Instances\Files;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Files\GetPath;
use Symfony\Component\Routing\Annotation\Route;

class GetPathController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private GetPath $getPath;

    public function __construct(GetPath $getPath)
    {
        $this->getPath = $getPath;
    }
    /**
     * @Route("", name="Get Instance Path Contents")
     */
    public function get(
        Host $host,
        string $container,
        string $path,
        bool $download
    ) {
        return $this->getPath->get($host, $container, $path, (int) $download);
    }
}
