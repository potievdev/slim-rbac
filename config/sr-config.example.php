<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = [__DIR__ . '/../src/models/entity'];

$dbParams = [
    'driver'   => 'pdo_mysql',
    'host'     => 'localhost',
    'user'     => 'root',
    'password' => '',
    'dbname'   => 'test',
    'port'     => 3306
];

$config = Setup::createAnnotationMetadataConfiguration($paths, false, null, null, false);

return EntityManager::create($dbParams, $config);