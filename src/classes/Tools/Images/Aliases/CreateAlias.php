<?php
namespace dhope0000\LXDClient\Tools\Images\Aliases;

use dhope0000\LXDClient\Objects\Host;

class CreateAlias
{
    public function create(Host $host, string $fingerprint, string $name, string $description = "")
    {
        return $host->images->aliases->create($fingerprint, $name, $description);
    }
}
