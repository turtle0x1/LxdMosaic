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
$totalStorageAvailable = 0;

foreach ($storagePools["clusters"] as $cluster) {
    foreach ($cluster["members"] as $host) {
        foreach ($host["pools"] as $pool) {
            $totalStorageUsage += $pool["resources"]["space"]["used"];
            $totalStorageAvailable += $pool["resources"]["space"]["total"];
        }
    }
}

foreach ($storagePools["standalone"]["members"] as $host) {
    foreach ($host["pools"] as $pool) {
        $totalStorageUsage += $pool["resources"]["space"]["used"];
        $totalStorageAvailable += $pool["resources"]["space"]["total"];
    }
}

$resourcesByHost = $getResources->getAllHostRecourses();
$totalMemory = 0;

foreach ($resourcesByHost as $host) {
    if (isset($host["online"]) && $host["online"] == false) {
        continue;
    }
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

$storeDetails->store($totalMemory, $activeContainers, $totalStorageUsage, $totalStorageAvailable);

exit(0);
