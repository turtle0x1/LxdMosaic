<?php

$_ENV = getenv();

date_default_timezone_set('UTC');

require __DIR__ . '/../../../vendor/autoload.php';

$container = new \DI\Container();

$updateVulns = $container->make("dhope0000\LXDClient\Tools\Vulnerabilities\UpdateVulnerabilities");

$result = $updateVulns->update();

echo "Vulnerability update complete.\n";
echo "  Active OSes:       {$result['active_os_count']}\n";
echo "  Vulns stored:      {$result['vulns_stored']}\n";
echo "  CVEs stored:       {$result['cves_stored']}\n";
echo "  Packages stored:   {$result['packages_stored']}\n";
echo "  Impacted instances:{$result['impacted_instances']}\n";

if (!empty($result['errors'])) {
    echo "  Errors:\n";
    foreach ($result['errors'] as $err) {
        echo "    - {$err}\n";
    }
}

if (!empty($result['error'])) {
    echo "  Error: {$result['error']}\n";
}
