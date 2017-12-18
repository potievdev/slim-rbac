<?php

/**
 * Configurations for entity manager
 */

return [
    'devMode' => true,
    'entitiesDir' => __DIR__ . '/../src/models/entity',
    'db' => [
        'driver'   => 'pdo_mysql',
        'host'     => 'localhost',
        'user'     => 'root',
        'password' => '',
        'dbname'   => 'test',
        'port'     => 3306
    ]
];
