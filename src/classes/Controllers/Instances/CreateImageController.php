<?php

namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Tools\Instances\CreateImage;
use dhope0000\LXDClient\Objects\Host;

class CreateImageController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(CreateImage $createImage)
    {
        $this->createImage = $createImage;
    }

    public function create(
        Host $host,
        string $container,
        string $imageAlias,
        bool $public,
        string $os = null,
        string $description = null
    ) {
        $this->createImage->create($host, $container, $imageAlias, $public, $os, $description);
        return ["success"=>"Creating Image"];
    }
}
