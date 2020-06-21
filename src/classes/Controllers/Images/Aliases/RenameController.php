<?php

namespace dhope0000\LXDClient\Controllers\Images\Aliases;

use dhope0000\LXDClient\Tools\Images\Aliases\RenameAlias;
use dhope0000\LXDClient\Objects\Host;

class RenameController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(RenameAlias $renameAlias)
    {
        $this->renameAlias = $renameAlias;
    }

    public function rename(Host $host, string $name, string $newName)
    {
        $lxdResponse = $this->renameAlias->rename($host, $name, $newName);
        return ["state"=>"success", "message"=>"Renamed alias", "lxdResponse"=>$lxdResponse];
    }
}
