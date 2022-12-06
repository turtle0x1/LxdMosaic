<?php

namespace dhope0000\LXDClient\Controllers\Images\Aliases;

use dhope0000\LXDClient\Tools\Images\Aliases\UpdateDescription;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class UpdateDescriptionController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private UpdateDescription $updateDescription;

    public function __construct(UpdateDescription $updateDescription)
    {
        $this->updateDescription = $updateDescription;
    }
    /**
     * @Route("", name="Update Image Alias Description")
     */
    public function update(Host $host, string $fingerprint, string $name, string $description = "") :array
    {
        $lxdResponse = $this->updateDescription->update($host, $fingerprint, $name, $description);
        return ["state"=>"success", "message"=>"Updated alias description", "lxdResponse"=>$lxdResponse];
    }
}
