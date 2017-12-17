<?php

/**
 * Configurations file
 */
return [
    'displayErrorDetails' => true,
    'devMode' => true,
    'entitiesDir' => __DIR__ . '/../models/entity',
    'db' => [
        'driver'   => 'pdo_mysql',
        'host'     => 'localhost',
        'user'     => 'slim',
        'password' => 'slim123',
        'dbname'   => 'slim',
        'port'     => 3306
    ]
];