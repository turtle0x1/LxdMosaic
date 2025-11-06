<?php

$_ENV = getenv();
date_default_timezone_set('UTC');
require __DIR__ . '/../../../vendor/autoload.php';

$container = new \DI\Container();

$import = $container->make("dhope0000\LXDClient\Tools\Instances\Metrics\ImportHostInsanceMetrics");

$o = $container->make("dhope0000\LXDClient\Tools\Instances\Metrics\GetHostContainerStatus");

$p = $o->get();

function importInstancesStats($member, $import)
{
    $instances = $member->getCustomProp('instances');
    $o = [];
    foreach ($instances as $instance) {
        if ($instance['pullMetrics']) {
            $o[] = $instance['name'];
        }
    }
    $import->import($member, $o);
}

foreach ($p['clusters'] as $cluster) {
    foreach ($cluster['members'] as $member) {
        if (!$member->hostOnline()) {
            continue;
        }
        importInstancesStats($member, $import);
    }
}

foreach ($p['standalone']['members'] as $member) {
    if (!$member->hostOnline()) {
        continue;
    }
    importInstancesStats($member, $import);
}
