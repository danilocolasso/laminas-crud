<?php

$localConfig = include __DIR__ . '/local.php';

return [
    'environments' => [
        'default_database' => 'development',
        'development' => [
            'adapter' => 'mysql',
            'host' => $localConfig['db']['host'],
            'name' => $localConfig['db']['database'],
            'user' => $localConfig['db']['username'],
            'pass' => $localConfig['db']['password'],
            'port' => $localConfig['db']['port'],
            'charset' => $localConfig['db']['charset'],
        ],
        // Define other environments if needed (e.g., testing, production)
    ],
];
