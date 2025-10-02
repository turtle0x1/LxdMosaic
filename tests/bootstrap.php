<?php

require __DIR__ . "/../vendor/autoload.php";

$env = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$env->load();
$env->required(['DB_HOST', 'DB_USER', 'DB_PASS', 'DB_NAME']);
