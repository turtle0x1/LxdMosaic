<?php

require __DIR__ . "/../vendor/autoload.php";

$env = (new Dotenv\Dotenv(__DIR__ . "/../"))->load();

$container = (new \DI\ContainerBuilder())->build();
$generateCert = $container->make("dhope0000\LXDClient\Tools\Hosts\GenerateCert");

$hosts = $container->make("dhope0000\LXDClient\Model\Hosts\HostList")->getOnlineHostsWithDetails();

$hasExtension = new dhope0000\LXDClient\Tools\Hosts\HasExtension();

$output = [];

$recursionLevel = 2;

function labelArrays(array $items, string $key)
{
    $out = [];
    foreach ($items as $item) {
        $out[$item[$key]] = $item;
    }
    return $out;
}


foreach ($hosts as $host) {
    $supportsProjects = $hasExtension->checkWithHost($host, "projects");
    $output[$host->getHostId()] = [];
    $allProjects = [["name"=>"default", "config"=>[]]];

    if ($supportsProjects) {
        $allProjects = $host->projects->all(2);
    }

    foreach ($allProjects as $project) {
        $projectName = $project["name"];

        $output[$host->getHostId()][$projectName] = [
            "instances"=>[],
            "networks"=>[],
            "storage"=>[],
            "images"=>[],
            "profiles"=>[]
        ];

        if ($supportsProjects) {
            $host->setProject($projectName);
        }

        $output[$host->getHostId()][$projectName]["instances"] = labelArrays($host->instances->all($recursionLevel), "name");

        if ($projectName == "default" || (isset($project["features.networks"]) && $project["features.networks"] === "true")) {
            $output[$host->getHostId()][$projectName]["networks"] = labelArrays($host->networks->all($recursionLevel), "name");
        }

        if ($projectName == "default" || (isset($project["features.storage"]) && $project["features.storage"] === "true")) {
            $output[$host->getHostId()][$projectName]["storage"] = labelArrays($host->storage->all($recursionLevel), "name");
        }

        if ($projectName == "default" || (isset($project["features.images"]) && $project["features.images"] === "true")) {
            $output[$host->getHostId()][$projectName]["images"] = labelArrays($host->images->all($recursionLevel), "fingerprint");
        }

        if ($projectName == "default" || (isset($project["features.profiles"]) && $project["features.profiles"] === "true")) {
            $output[$host->getHostId()][$projectName]["profiles"] = labelArrays($host->profiles->all($recursionLevel), "name");
        }
    }
}

file_put_contents(__DIR__ . "/cache.json", json_encode($output));
