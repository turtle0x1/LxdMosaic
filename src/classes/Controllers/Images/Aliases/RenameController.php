<?php

namespace dhope0000\LXDClient\Controllers\Images\Aliases;

use dhope0000\LXDClient\Tools\Images\Aliases\RenameAlias;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class RenameController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private RenameAlias $renameAlias;

    public function __construct(RenameAlias $renameAlias)
    {
        $this->renameAlias = $renameAlias;
    }
    /**
     * @Route("", name="Rename Image Alias")
     */
    public function rename(Host $host, string $name, string $newName) :array
    {
        $lxdResponse = $this->renameAlias->rename($host, $name, $newName);
        return ["state"=>"success", "message"=>"Renamed alias", "lxdResponse"=>$lxdResponse];
    }
}
