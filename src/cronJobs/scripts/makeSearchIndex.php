<?php

use dhope0000\LXDClient\Model\Search\Index\InsertIndex;
use dhope0000\LXDClient\Tools\Search\CreateSearchIndex;
use dhope0000\LXDClient\Model\Search\Index\DeleteIndex;

$_ENV = getenv();

date_default_timezone_set("UTC");

require __DIR__ . "/../../../vendor/autoload.php";

$container = (new \DI\ContainerBuilder())->build();

$createSearchIndex = $container->make(CreateSearchIndex::class);
$insertIndex = $container->make(InsertIndex::class);
$deleteIndex = $container->make(DeleteIndex::class);

$softwareAssets = $createSearchIndex->create();
$newIndexId = $insertIndex->insert($softwareAssets);
$deleteIndex->deleteWhereIdNot($newIndexId);
