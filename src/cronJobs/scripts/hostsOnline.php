#!/usr/bin/env php
<?php

$_ENV = getenv();

require __DIR__ . "/../../../vendor/autoload.php";

$builder = new \DI\ContainerBuilder();
$container = $builder->build();

$env = new Dotenv\Dotenv(__DIR__ . "/../../../");
$env->load();

$hostList = $container->make("dhope0000\LXDClient\Model\Hosts\HostList");
$details = $container->make("dhope0000\LXDClient\Model\Hosts\GetDetails");
$clients = $container->make("dhope0000\LXDClient\Model\Client\LxdClient");
$changeStatus = $container->make("dhope0000\LXDClient\Model\Hosts\ChangeStatus");
$reloadNode = $container->make("dhope0000\LXDClient\Tools\Node\Hosts");

$allHosts = $hostList->getHostListWithDetails();

function disableHost($hostId, $urlAndPort, $sendMessageAndReload = true, $changeStatus, $reloadNode)
{
    $changeStatus->setOffline($hostId);
    if ($sendMessageAndReload) {
        $reloadNode->sendMessage("hostChange", ["host"=>$urlAndPort,"offline"=>true]);
        $reloadNode->reloadHosts();
    }
}

foreach ($allHosts as $host) {
    try {
        $pathToCert = $details->getCertificate($host["Host_ID"]);
        $pathToCert = $_ENV["LXD_CERTS_DIR"] . "$pathToCert";
        $config = $clients->createConfigArray($pathToCert);
        $config["timeout"] = 2;
        $lxd = $clients->createNewClient($host["Host_Url_And_Port"], $config);
        $lxd->host->info();
        $changeStatus->setOnline($host["Host_ID"]);

        if ($host["Host_Online"] != true) {
            $reloadNode->sendMessage("hostChange", ["host"=>$host["Host_Url_And_Port"],"offline"=>false]);
            $reloadNode->reloadHosts();
        }
    } catch (\Http\Client\Exception\NetworkException $e) {
        disableHost($host["Host_ID"], $host["Host_Url_And_Port"], $host["Host_Online"] == true, $changeStatus, $reloadNode);
    } catch (\Http\Client\Exception\HttpException $e) {
        // Well this may be interesting cause you capture an error like this
        // from a broken cluster
        // "failed to begin transaction: call exec-sql (budget 0s): receive: header: EOF"
        // which is pretty useful i guess to log
        disableHost($host["Host_ID"], $host["Host_Url_And_Port"], $host["Host_Online"] == true, $changeStatus, $reloadNode);
    } finally {
        $reloadNode->reloadHosts();
    }
}
