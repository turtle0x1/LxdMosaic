<?php

$_ENV = getenv();

require __DIR__ . "/../../../vendor/autoload.php";

use dhope0000\LXDClient\Constants\Constants;

$builder = new \DI\ContainerBuilder();
$container = $builder->build();

$import = $container->make("dhope0000\LXDClient\Tools\Instances\Metrics\ImportHostInsanceMetrics");

$o = $container->make("dhope0000\LXDClient\Tools\Instances\Metrics\GetHostContainerStatus");

$p = $o->get();

function importInstancesStats($member, $import)
{
    $instances = $member->getCustomProp("instances");
    $o = [];
    foreach ($instances as $instance) {
        if ($instance["pullMetrics"]) {
            $o[] = $instance["name"];
        }
    }
    $import->import($member, $o);
}

foreach ($p["clusters"] as $cluster) {
    foreach ($cluster["members"] as $member) {
        importInstancesStats($member, $import);
    }
}

foreach ($p["standalone"]["members"] as $member) {
    importInstancesStats($member, $import);
}
