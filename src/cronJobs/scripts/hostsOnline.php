#!/usr/bin/env php
<?php

$_ENV = getenv();
date_default_timezone_set('UTC');
require __DIR__ . '/../../../vendor/autoload.php';

$container = new \DI\Container();

(\Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../'))->load();

$hostList = $container->make("dhope0000\LXDClient\Model\Hosts\HostList");
$details = $container->make("dhope0000\LXDClient\Model\Hosts\GetDetails");
$clients = $container->make("dhope0000\LXDClient\Model\Client\LxdClient");
$changeStatus = $container->make("dhope0000\LXDClient\Model\Hosts\ChangeStatus");

$allHosts = $hostList->getHostListWithDetails();

function disableHost($hostId, $changeStatus)
{
    $changeStatus->setOffline($hostId);
}

foreach ($allHosts as $host) {
    try {
        $pathToCert = $details->getCertificate($host['Host_ID']);
        $pathToCert = $_ENV['LXD_CERTS_DIR'] . "{$pathToCert}";
        $socketPath = $details->getSocketPath($host['Host_ID']);

        if ($socketPath == null) {
            $certinfo = openssl_x509_parse(file_get_contents($pathToCert));

            if ($certinfo['validFrom_time_t'] > time() || $certinfo['validTo_time_t'] < time()) {
                disableHost($host['Host_ID'], $changeStatus);
                continue;
            }
        }

        $config = $clients->createConfigArray($pathToCert, $socketPath);
        $config['timeout'] = 2;
        $lxd = $clients->createNewClient($host['Host_Url_And_Port'], $config);
        $lxd->host->info();
        $changeStatus->setOnline($host['Host_ID']);
    } catch (\Http\Client\Exception\NetworkException) {
        disableHost($host['Host_ID'], $changeStatus);
    } catch (\Http\Client\Exception\HttpException) {
        // Well this may be interesting cause you capture an error like this
        // from a broken cluster
        // "failed to begin transaction: call exec-sql (budget 0s): receive: header: EOF"
        // which is pretty useful i guess to log
        disableHost($host['Host_ID'], $changeStatus);
    }
}
