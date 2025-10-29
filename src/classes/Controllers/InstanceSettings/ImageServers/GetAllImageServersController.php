<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings\ImageServers;

use dhope0000\LXDClient\Model\InstanceSettings\ImageServers\FetchImageServers;
use Symfony\Component\Routing\Annotation\Route;

class GetAllImageServersController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $fetchImageServers;

    public function __construct(FetchImageServers $fetchImageServers)
    {
        $this->fetchImageServers = $fetchImageServers;
    }
    /**
     * @Route("", name="Get all image servers aliases")
     */
    public function all()
    {
        $aliases = $this->fetchImageServers->fetchAllAliases();
        return ["state" => "success", "servers" => $aliases];
    }
}
