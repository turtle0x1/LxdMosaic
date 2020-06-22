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
     * @Route("", name="Rename Instance")
     */
    public function rename(
        Host $host,
        string $container,
        string $newContainer,
        string $alias = null
    ) {
        $result = $this->renameInstance->rename($host, $container, $newContainer);
        return [
            "state"=>"success",
            "message"=>"Renaming $alias/$container to $alias/$newContainer",
            "lxdResponse"=>$result
        ];
    }
}
