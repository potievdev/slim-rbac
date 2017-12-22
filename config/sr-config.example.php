<?php

/**
 * If you not include vendor/autoload.php before, remove comment tags.
 * require_once __DIR__ .'/vendor/autoload.php';
 */

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$dbParams = [
    'driver'   => 'pdo_mysql',
    'host'     => 'localhost',
    'user'     => 'root',
    'password' => '',
    'dbname'   => 'test',
    'port'     => 3306
];

$config = Setup::createAnnotationMetadataConfiguration([], false, null, null, false);

return EntityManager::create($dbParams, $config);