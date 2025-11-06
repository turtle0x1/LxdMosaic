<?php

namespace dhope0000\LXDClient\Tools\Images\Aliases;

use dhope0000\LXDClient\Objects\Host;

class DeleteAlias
{
    public function delete(Host $host, string $name)
    {
        return $host->images->aliases->remove($name);
    }
}
