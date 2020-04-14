<?php

require __DIR__ . "/../../vendor/autoload.php";

$builder = new \DI\ContainerBuilder();
$container = $builder->build();

$env = new Dotenv\Dotenv(__DIR__ . "/../../");
$env->load();

$hostList = $container->make("dhope0000\LXDClient\Model\Hosts\HostList");
$details = $container->make("dhope0000\LXDClient\Model\Hosts\GetDetails");
$clients = $container->make("dhope0000\LXDClient\Model\Client\LxdClient");
$changeStatus = $container->make("dhope0000\LXDClient\Model\Hosts\ChangeStatus");
$reloadNode = $container->make("dhope0000\LXDClient\Tools\Node\Hosts");


$allHosts = $hostList->getHostListWithDetails();

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
        $changeStatus->setOffline($host["Host_ID"]);
        if ($host["Host_Online"] == true) {
            $reloadNode->sendMessage("hostChange", ["host"=>$host["Host_Url_And_Port"],"offline"=>true]);
            $reloadNode->reloadHosts();
        }
    } finally {
        $reloadNode->reloadHosts();
    }
}
