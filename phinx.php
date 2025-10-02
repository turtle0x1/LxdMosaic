<?php

(\Dotenv\Dotenv::createImmutable(__DIR__))->load();

return
    [
        'paths' => [
            'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        ],
        'environments' =>
            [
                'default_database' => $_ENV['DB_NAME'] ?? "MISSING ENV VARIABLE",
                'default_migration_table' => 'Phinx_Log',
                'mysql' =>
                    [
                        'adapter' => 'mysql',
                        'host' => $_ENV['DB_HOST'] ?? "MISSING ENV VARIABLE",
                        'name' => $_ENV['DB_NAME'] ?? "MISSING ENV VARIABLE",
                        'user' => $_ENV['DB_USER'] ?? "MISSING ENV VARIABLE",
                        'pass' => $_ENV['DB_PASS'] ?? "MISSING ENV VARIABLE",
                        'port' => 3306,
                        'charset' => 'utf8',
                        'collation' => 'utf8_unicode_ci',
                    ],
                'snap' =>
                    [
                        'adapter' => 'sqlite',
                        'name' => $_ENV["SNAP_DATA"] ?? "MISSING ENV VARIABLE" . "/lxdMosaic",
                        'suffix' => '.db'
                    ],
            ],
    ];
