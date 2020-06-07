<?php
namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Objects\Host;

class DeleteInstances
{
    public function delete(Host $host, array $instances)
    {
        foreach ($instances as $instance) {
            $state = $host->instances->state($instance);

            if ($state["status_code"] == 103) {
                $host->instances->setState($instance, "stop");
            }

            $host->instances->remove($instance, true);
        }

        return true;
    }
}
