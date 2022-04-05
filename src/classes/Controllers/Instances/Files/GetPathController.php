<?php

namespace dhope0000\LXDClient\Controllers\Instances\Files;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Files\GetPath;
use Symfony\Component\Routing\Annotation\Route;

class GetPathController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $getPath;

    public function __construct(GetPath $getPath)
    {
        $this->getPath = $getPath;
    }
    /**
     * @Route("/api/Instances/Files/GetPathController/get", methods={"POST"}, name="Get Instance Path Contents", options={"rbac" = "instances.files.read"})
     */
    public function get(
        Host $host,
        string $container,
        string $path,
        $download
    ) {
        return $this->getPath->get($host, $container, $path, (int) $download);
    }
}
