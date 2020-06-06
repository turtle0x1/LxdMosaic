<?php

namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Tools\Instances\CreateImage;

class CreateImageController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(CreateImage $createImage)
    {
        $this->createImage = $createImage;
    }

    public function create(
        int $hostId,
        string $container,
        string $imageAlias,
        bool $public,
        string $os = null,
        string $description = null
    ) {
        $this->createImage->create($hostId, $container, $imageAlias, $public, $os, $description);
        return ["success"=>"Creating Image"];
    }
}
