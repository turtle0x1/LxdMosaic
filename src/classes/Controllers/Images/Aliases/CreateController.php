<?php

namespace dhope0000\LXDClient\Controllers\Images\Aliases;

use dhope0000\LXDClient\Tools\Images\Aliases\CreateAlias;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class CreateController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private CreateAlias $createAlias;

    public function __construct(CreateAlias $createAlias)
    {
        $this->createAlias = $createAlias;
    }
    /**
     * @Route("", name="Create Image Alias")
     */
    public function create(Host $host, string $fingerprint, string $name, string $description = "") :array
    {
        $lxdResponse = $this->createAlias->create(
            $host,
            $fingerprint,
            $name,
            $description
        );

        return ["state"=>"success", "message"=>"Create alias", "lxdResponse"=>$lxdResponse];
    }
}
