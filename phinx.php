<?php

// load our environment files - used to store credentials & configuration
(new Dotenv\Dotenv(__DIR__))->load();

return
    [
        'paths' => [
            'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        ],
        'environments' =>
            [
                'default_database' => $_ENV['DB_NAME'],
                'default_migration_table' => 'Phinx_Log',
                'mysql' =>
                    [
                        'adapter' => 'mysql',
                        'host' => $_ENV['DB_HOST'],
                        'name' => $_ENV['DB_NAME'],
                        'user' => $_ENV['DB_USER'],
                        'pass' => $_ENV['DB_PASS'],
                        'port' => 3306,
                        'charset' => 'utf8',
                        'collation' => 'utf8_unicode_ci',
                    ],
            ],
    ];
