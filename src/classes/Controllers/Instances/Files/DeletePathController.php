<?php

namespace dhope0000\LXDClient\Controllers\Instances\Files;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Files\DeletePath;
use Symfony\Component\Routing\Annotation\Route;

class DeletePathController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $deletePath;

    public function __construct(DeletePath $deletePath)
    {
        $this->deletePath = $deletePath;
    }
    /**
     * @Route("/api/Instances/Files/DeletePathController/delete", name="Delete Instance File", methods={"POST"})
     */
    public function delete(
        Host $host,
        string $container,
        string $path
    ) {
        $response = $this->deletePath->delete($host, $container, $path);

        return ["state"=>"success", "message"=>"Deleted item", "lxdResponse"=>$response];
    }
}
