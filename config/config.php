<?php

return [
    'db' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'port' => getenv('DB_PORT') ?: '3306',
        'database' => getenv('DB_DATABASE') ?: 'elementalys',
        'username' => getenv('DB_USERNAME') ?: 'lotta',
        'password' => getenv('DB_PASSWORD') ?: 'test',
        'charset' => 'utf8mb4'
    ],
    'app' => [
        'name' => 'Elementalys'
    ]
];
