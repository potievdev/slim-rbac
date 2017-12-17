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
        'user'     => 'username',
        'password' => 'password',
        'dbname'   => 'database_name',
        'port'     => 3306
    ]
];