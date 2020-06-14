<?php
namespace dhope0000\LXDClient\Tools\Images\Aliases;

use dhope0000\LXDClient\Objects\Host;

class UpdateDescription
{
    public function update(Host $host, string $fingerprint, string $name, string $description = "")
    {
        return $host->images->aliases->replace($name, $fingerprint, $description);
    }
}
