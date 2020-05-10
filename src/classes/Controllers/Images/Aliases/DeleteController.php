<?php

namespace dhope0000\LxdClient\Controllers\Images\Aliases;

use dhope0000\LXDClient\Tools\Images\Aliases\DeleteAlias;

class DeleteController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(DeleteAlias $deleteAlias)
    {
        $this->deleteAlias = $deleteAlias;
    }

    public function delete(int $hostId, string $name)
    {
        $lxdResponse = $this->deleteAlias->delete($hostId, $name);
        return ["state"=>"success", "message"=>"Deleted alias", "lxdResponse"=>$lxdResponse];
    }
}
