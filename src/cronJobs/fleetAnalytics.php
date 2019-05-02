<?php

require __DIR__ . "/../../vendor/autoload.php";

$builder = new \DI\ContainerBuilder();
$container = $builder->build();

$env = new Dotenv\Dotenv(__DIR__ . "/../../");
$env->load();

$getResources = $container->make("dhope0000\LXDClient\Tools\Hosts\GetResources");
$getAllContainers = $container->make("dhope0000\LXDClient\Tools\Containers\GetHostsContainers");
$getStorageDetails = $container->make("dhope0000\LXDClient\Tools\Storage\GetHostsStorage");
$storeDetails = $container->make("dhope0000\LXDClient\Model\Analytics\StoreFleetAnalytics");

$storagePools = $getStorageDetails->getAll();

$totalStorageUsage = 0;

foreach($storagePools as $host => $details){
    foreach($details["pools"] as $pool){
        $totalStorageUsage += $pool["resources"]["space"]["used"];
    }
}

$resourcesByHost = $getResources->getAllHostRecourses();
$totalMemory = 0;

foreach ($resourcesByHost as $host) {
    $totalMemory += $host["memory"]["used"];
}

$containersByHost = $getAllContainers->getHostsContainers();

$activeContainers = 0;

foreach ($containersByHost as $host) {
    foreach ($host["containers"] as $container) {
        if ($container["state"]["status_code"] == 103) {
            $activeContainers++;
        }
    }
}

$storeDetails->store($totalMemory, $activeContainers, $totalStorageUsage);

exit(0);
