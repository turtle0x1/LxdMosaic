<?php

namespace dhope0000\LxdClient\Controllers\Images\Aliases;

use dhope0000\LXDClient\Tools\Images\Aliases\UpdateDescription;
use dhope0000\LXDClient\Objects\Host;

class UpdateDescriptionController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(UpdateDescription $updateDescription)
    {
        $this->updateDescription = $updateDescription;
    }

    public function update(Host $host, string $fingerprint, string $name, string $description = "")
    {
        $lxdResponse = $this->updateDescription->update($host, $fingerprint, $name, $description);
        return ["state"=>"success", "message"=>"Updated alias description", "lxdResponse"=>$lxdResponse];
    }
}
