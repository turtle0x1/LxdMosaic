<?php
namespace dhope0000\LXDClient\Tools\Instances\Comments;

use dhope0000\LXDClient\Objects\Host;

class SetInstanceComment
{
    public function set(Host $host, string $instanceName, string $comment)
    {
        $instance = $host->instances->info($instanceName);

        $instance["config"]["user.comment"] = $comment;

        if (empty($instance["devices"])) {
            unset($instance["devices"]);
        }

        $host->instances->replace($instanceName, $instance);
        
        return true;
    }
}
