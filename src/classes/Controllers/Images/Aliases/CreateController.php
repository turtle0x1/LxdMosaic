<?php

namespace dhope0000\LxdClient\Controllers\Images\Aliases;

use dhope0000\LXDClient\Tools\Images\Aliases\CreateAlias;

class CreateController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(CreateAlias $createAlias)
    {
        $this->createAlias = $createAlias;
    }

    public function create(int $hostId, string $fingerprint, string $name, string $description = "")
    {
        $lxdResponse = $this->createAlias->create(
            $hostId,
            $fingerprint,
            $name,
            $description
        );

        return ["state"=>"success", "message"=>"Create alias", "lxdResponse"=>$lxdResponse];
    }
}
