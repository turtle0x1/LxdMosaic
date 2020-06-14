<?php
namespace dhope0000\LXDClient\Tools\Images\Aliases;

use dhope0000\LXDClient\Objects\Host;

class RenameAlias
{
    public function rename(Host $host, string $name, string $newName)
    {
        return $host->images->aliases->rename($name, $newName);
    }
}
