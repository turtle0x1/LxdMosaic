<?php

namespace dhope0000\LXDClient\Tools\Instances\Metrics;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Tools\Profiles\GetAllProfiles;

class GetHostContainerStatus
{
    public function __construct(LxdClient $lxdClient, GetAllProfiles $getAllProfiles)
    {
        $this->lxdClient = $lxdClient;
        $this->getAllProfiles = $getAllProfiles;
    }

    public function get()
    {
        $allProfiles = $this->getAllProfiles->getAllProfiles();

        $output = [];

        foreach($allProfiles as $host => $details){
            $instancesToScan = ["pullMetrics"=>[]];

            foreach($details["profiles"] as $profile){
                $pDetails = $profile["details"];

                if(!isset($pDetails["config"])){
                    continue;
                }

                $config = $pDetails["config"];

                $pullMetrics = isset($config["environment.lxdMosaicPullMetrics"]);

                if(!$pullMetrics){
                    continue;
                }



                foreach($pDetails["used_by"] as $instance){
                    $instance = str_replace("/1.0/instances/", "", $instance);
                    $instance = str_replace("/1.0/containers/", "", $instance);

                    $instancesToScan["pullMetrics"][] = $instance;
                }
            }
            $client = $this->lxdClient->getANewClient($details["hostId"]);
            $allInstances = $client->instances->all();
            unset($details["profiles"]);
            $details["instances"] = [];
            foreach($allInstances as $instance){
                $details["instances"][] = [
                    "name"=>$instance,
                    "pullMetrics"=>in_array($instance, $instancesToScan["pullMetrics"])
                ];
            }
            $output[$host] = $details;

        }
        return $output;
    }
}
