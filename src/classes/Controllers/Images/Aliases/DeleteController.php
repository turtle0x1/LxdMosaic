<?php

namespace dhope0000\LXDClient\Controllers\Images\Aliases;

use dhope0000\LXDClient\Tools\Images\Aliases\DeleteAlias;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class DeleteController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(DeleteAlias $deleteAlias)
    {
        $this->deleteAlias = $deleteAlias;
    }
    /**
     * @Route("/api/Images/Aliases/DeleteController/delete", methods={"POST"}, name="Delete Image Alias")
     */
    public function delete(Host $host, string $name)
    {
        $lxdResponse = $this->deleteAlias->delete($host, $name);
        return ["state"=>"success", "message"=>"Deleted alias", "lxdResponse"=>$lxdResponse];
    }
}
