<?php

define('ROOT_DIR', dirname(__DIR__));

require_once ROOT_DIR . '/vendor/autoload.php';

use Potievdev\SlimRbac\AuthManager;
use Potievdev\SlimRbac\Models\Entity\Permission;
use Potievdev\SlimRbac\Models\Entity\Role;
use Potievdev\SlimRbac\Structure\AuthManagerOptions;

$helperSet = include ROOT_DIR . '/cli-config.php';

/** @var \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper $entityManagerHelper */
$entityManagerHelper = $helperSet->get('em');

/** @var \Doctrine\ORM\EntityManager $entityManager */
$entityManager = $entityManagerHelper->getEntityManager();

$authManagerOptions = new AuthManagerOptions();
$authManagerOptions->setEntityManager($entityManager);

$authManager = new AuthManager($authManagerOptions);

$authManager->removeAll();

$edit = new Permission();
$edit->setName('edit');
$authManager->addPermission($edit);

$write = new Permission();
$write->setName('write');
$authManager->addPermission($write);

$moderator = new Role();
$moderator->setName('moderator');
$authManager->addRole($moderator);

$admin = new Role();
$admin->setName('admin');
$authManager->addRole($admin);

$authManager->addPermissionToRole($moderator, $edit);
$authManager->addPermissionToRole($admin, $write);
$authManager->addChildRoleToRole($admin, $moderator);

$moderatorUserId = 1;
$adminUserId = 2;
$anotherUser = 3;

$authManager->assignRoleToUser($moderatorUserId, $moderator);
$authManager->assignRoleToUser($adminUserId, $admin);



