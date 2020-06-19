<?php

use TriangleFireSystems\Common\Enviroment\ImportEnviroment;

require __DIR__ . "/../../vendor/autoload.php";

$importEnviroment = new ImportEnviroment;
$importEnviroment->importEnviroment(__DIR__ . "/../../");

$builder = new \DI\ContainerBuilder();

$builder->addDefinitions(__DIR__ . '/../../src/config/diDefinitions.php');

$container = $builder->build();

$x = $container->make("TriangleFireSystems\Common\Lego\FindLegoControllers");

$x->loadAndCache($container);
