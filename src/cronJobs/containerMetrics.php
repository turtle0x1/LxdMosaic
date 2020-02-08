<?php

require __DIR__ . "/../../vendor/autoload.php";

use dhope0000\LXDClient\Constants\Constants;

$builder = new \DI\ContainerBuilder();
$container = $builder->build();

$env = new Dotenv\Dotenv(__DIR__ . "/../../");
$env->load();

$hostList = $container->make("dhope0000\LXDClient\Model\Hosts\HostList");


$getP = $container->make("dhope0000\LXDClient\Tools\Profiles\GetAllProfiles");
$import = $container->make("dhope0000\LXDClient\Tools\Instances\Metrics\ImportHostInsanceMetrics");

$allProfiles = $getP->getAllProfiles();

foreach($allProfiles as $host => $details){
    $instancesToScan = [];

    foreach($details["profiles"] as $profile){
        $pDetails = $profile["details"];

        if(!isset($pDetails["config"])){
            continue;
        }

        $config = $pDetails["config"];

        if(!isset($config["environment.lxdMosaicPullMetrics"])){
            continue;
        }

        foreach($pDetails["used_by"] as $instance){
            $instance = str_replace("/1.0/instances/", "", $instance);
            $instance = str_replace("/1.0/containers/", "", $instance);
            $instancesToScan[] = $instance;
        }
    }

    $instancesToScan = array_unique($instancesToScan);
    // var_dump($details);
    $import->import($details["hostId"], $instancesToScan);

}
