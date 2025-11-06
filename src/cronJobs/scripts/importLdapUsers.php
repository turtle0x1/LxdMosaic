<?php

$_ENV = getenv();
date_default_timezone_set('UTC');
require __DIR__ . '/../../../vendor/autoload.php';

$container = new \DI\Container();

$importUsers = $container->make("dhope0000\LXDClient\Tools\User\Ldap\ImportLdapUsers");

$importUsers->import();
