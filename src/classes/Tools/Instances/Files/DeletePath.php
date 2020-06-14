<?php

namespace dhope0000\LXDClient\Tools\Instances\Files;

use dhope0000\LXDClient\Objects\Host;

class DeletePath
{
    public function delete(Host $host, string $instance, string $path)
    {
        return $host->instances->files->remove($instance, $path);
    }
}
