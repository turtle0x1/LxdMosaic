<?php

require __DIR__ . "/../vendor/autoload.php";

(\Dotenv\Dotenv::createImmutable(__DIR__ . '/../'))->load();
$env->required(['DB_HOST', 'DB_USER', 'DB_PASS', 'DB_NAME']);
