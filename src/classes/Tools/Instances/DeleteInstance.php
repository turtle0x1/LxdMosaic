<?php
namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Objects\Host;

class DeleteInstance
{
    public function delete(Host $host, string $instance)
    {
        return $host->instances->remove($instance);
    }
}
