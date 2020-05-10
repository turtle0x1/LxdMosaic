<?php

namespace dhope0000\LxdClient\Controllers\Images\Aliases;

use dhope0000\LXDClient\Tools\Images\Aliases\RenameAlias;

class RenameController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(RenameAlias $renameAlias)
    {
        $this->renameAlias = $renameAlias;
    }

    public function rename(int $hostId, string $name, string $newName)
    {
        $lxdResponse = $this->renameAlias->rename($hostId, $name, $newName);
        return ["state"=>"success", "message"=>"Renamed alias", "lxdResponse"=>$lxdResponse];
    }
}
