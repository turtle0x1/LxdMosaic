<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Tools\CloudConfig\DeployToProfile;
use dhope0000\LXDClient\Tools\Utilities\StringTools;

class Deploy
{
    public function __construct(DeployToProfile $deployToProfile)
    {
        $this->deployToProfile = $deployToProfile;
    }

    public function deploy(int $deploymentId, array $instances)
    {
        $this->validateInstances($instances);

        $revProfileNames = [];
        foreach($instances as $instance){
            if(!isset($revProfileNames[$instance["revId"]])){
                $profileName = StringTools::random(12);
                $revProfileNames[$instance["revId"]] = $profileName;
                $this->deployToProfile->deployToHosts(
                    $profileName,
                    [1],
                    null,
                    $instance["revId"]
                );
            }


        }
    }

    public function validateInstances(array $instances)
    {
        foreach($instances as $instance){
            if(!isset($instance["revId"]) || !is_numeric($instance["revId"])){
                throw new \Exception("Missing rev id", 1);
            } elseif(!isset($instance["revId"]) || !is_numeric($instance["revId"])){
                throw new \Exception("Missing the number of instances", 1);
            }
        }
        return true;
    }
}
