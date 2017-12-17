<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$config = require 'config.php';

$paths = [$config['entitiesDir']];
$isDevMode = $config['devMode'];
$dbParams = $config['db'];

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
$entityManager = EntityManager::create($dbParams, $config);

$helperSet = new \Symfony\Component\Console\Helper\HelperSet([
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($entityManager->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($entityManager)
]);

return $helperSet;