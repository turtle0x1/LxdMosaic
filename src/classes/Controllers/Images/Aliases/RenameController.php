<?php

namespace dhope0000\LXDClient\Controllers\Images\Aliases;

use dhope0000\LXDClient\Tools\Images\Aliases\RenameAlias;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class RenameController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(RenameAlias $renameAlias)
    {
        $this->renameAlias = $renameAlias;
    }
    /**
     * @Route("/api/Images/Aliases/RenameController/rename", methods={"POST"}, name="Rename Image Alias", options={"rbac" = "images.alias.rename"})
     */
    public function rename(Host $host, string $name, string $newName)
    {
        $lxdResponse = $this->renameAlias->rename($host, $name, $newName);
        return ["state"=>"success", "message"=>"Renamed alias", "lxdResponse"=>$lxdResponse];
    }
}
