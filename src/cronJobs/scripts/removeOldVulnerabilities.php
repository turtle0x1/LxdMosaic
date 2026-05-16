<?php

$_ENV = getenv();
date_default_timezone_set('UTC');
require __DIR__ . '/../../../vendor/autoload.php';

$container = new \DI\Container();

$removeHistory = $container->make("dhope0000\LXDClient\Tools\Vulnerabilities\RemoveVulnerabilityHistory");

$count = $removeHistory->remove(30);

echo "Vulnerability cleanup complete.\n";
echo "  Vulns removed: {$count}\n";
