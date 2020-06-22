<?php


require "vendor/autoload.php";

$env = new Dotenv\Dotenv(__DIR__);
$env->load();

$container = (new \DI\ContainerBuilder())->build();

// $gc = $container->make("dhope0000\LXDClient\Tools\Dashboard\GetDashboard");
//
// $gc->get();
//

// $x = dhope0000\LXDClient\Tools\Utilities\IsUpToDate::isIt();
// var_dump($x);



$x = $container->make("dhope0000\LXDClient\Model\Hosts\GetDetails");

$host = $x->fetchHost(13);

$output = $host->instances->execute("lxdMosaic", "cat /proc/loadavg", $record = true, [], true);
$averages = trim($host->instances->logs->read("lxdMosaic", $output["output"][0]));
$x = explode(" ", $averages);
$dateTime = (new \DateTimeImmutable())->format("Y-m-d H:i:s");

$host->instances->logs->remove("lxdMosaic", $output["output"][0]);
$host->instances->logs->remove("lxdMosaic", $output["output"][1]);
var_dump([
    "1 minute"=>$x[0],
    "5 minutes"=>$x[1],
    "15 minutes"=>$x[2]
]);
return true;

// $instances = ["c0-node0", "c0-node1", "c1-node1", "lxdMosaic", "music"];
//
//
//
// foreach ($instances as $instance) {
//     $logs = $host->instances->logs->all($instance);
//     var_dump($instance);
//     var_dump($logs);
//     // foreach ($logs as $log) {
//     //     if ($log == "lxc.log" || $log  == "lxc.conf") {
//     //         continue;
//     //     }
//     //     $host->instances->logs->remove($instance, $log);
//     // }
// }
//
//
// // var_dump($x->get());
//
// // $x = $client->images->all();
// // var_dump($x);
//
// //
// //
// // //
// // $client = $lxdClient->getANewClient(14);
// //
// // $file = file_get_contents("src/sensitiveData/backups/14/wasss/backup.2019-10-26T13:38:24+01:00.tar.gz");
// //
// // $x = $client->containers->create(
// //     "ras",
// //     [
// //         "source"=>"backup",
// //         "file"=>$file
// //     ],
// //     true
// // );
//
// // $p = $container->make("dhope0000\LXDClient\Tools\Backups\StoreBackupLocally");
//
// // $client = $lxdClient->getANewClient(14);
// // $p->store(14, "test", "wow");
//
// // $x = $client->containers->create("test", ["source"=>"/var/www/LxdMosaic/src/sensitiveData/backups/14/test/backup.2019-10-19T21:19:24+01:00.tar.gz"]);
//
//
//
// // $clusterClient->certificates->add($client->host->info()["environment"]["certificate"], "test123");
//
//
// // $x = $client->cluster->info("cluster-node2");
//
//
// // var_dump($client->cluster->info());
// // exit;
// //
// // $g = stream_context_create(array("ssl" => array("capture_peer_cert" => true, "verify_peer"=>false)));
// // $r = stream_socket_client(
//     "ssl://192.168.42.240:8443",
//     $errno,
//     $errstr,
//     30,
//     STREAM_CLIENT_CONNECT,
//     $g
// );
// $cont = stream_context_get_params($r);
// $cert = $cont["options"]["ssl"]["peer_certificate"];
//
// openssl_x509_export($cont["options"]["ssl"]["peer_certificate"], $cert);
//
// var_dump($cert);
//
// $x = $client->certificates->add($cert, "test123", "192.168.42.240:8443");
// var_dump($x);
// exit;
//
// $client->cluster->join([
//     "server_name"=> "server-3",
//     "server_address"=> "10.233.30.23:8443",
//     "enabled"=> true,
//     "cluster_address"=> "10.233.30.54:8443",
//     "member_config"=>[
//         ["entity"=>"storage-pool",
//         "name"=>"local",
//         "key"=>"source",
//         "value"=>"local",
//         "description"=>'"source" property for storage pool "local"']
//     ],
//     "cluster_certificate"=>$cert,
//     "cluster_password"=> "test123",
// ]);
