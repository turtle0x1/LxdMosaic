<?php
namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Objects\Host;

class RenameInstance
{
    public function rename(Host $host, string $instance, string $newInstance)
    {
        return $host->instances->rename($instance, $newInstance, true);
    }
}
