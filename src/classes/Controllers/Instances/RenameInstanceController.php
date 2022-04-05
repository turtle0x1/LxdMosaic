<?php
namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Tools\Instances\RenameInstance;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class RenameInstanceController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(RenameInstance $renameInstance)
    {
        $this->renameInstance = $renameInstance;
    }
    /**
     * @Route("/api/Instances/RenameInstanceController/rename", methods={"POST"}, name="Rename Instance", options={"rbac" = "instances.rename"})
     */
    public function rename(
        Host $host,
        string $container,
        string $newContainer
    ) {
        $result = $this->renameInstance->rename($host, $container, $newContainer);
        return [
            "state"=>"success",
            "message"=>"Renaming {$host->getAlias()}/$container to {$host->getAlias()}/$newContainer",
            "lxdResponse"=>$result
        ];
    }
}
