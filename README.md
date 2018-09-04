<div align="center">
    <h1> Slim3 RBAC Middleware </h1>
</div>

<div align="center">
[![Build Status](https://travis-ci.org/potievdev/slim-rbac.svg?branch=master)](https://travis-ci.org/potievdev/slim-rbac)
[![codecov](https://codecov.io/gh/potievdev/slim-rbac/branch/master/graph/badge.svg)](https://codecov.io/gh/potievdev/slim-rbac)
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Fpotievdev%2Fslim-rbac.svg?type=shield)](https://app.fossa.io/projects/git%2Bgithub.com%2Fpotievdev%2Fslim-rbac?ref=badge_shield)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/potievdev/slim-rbac/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/potievdev/slim-rbac/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/potievdev/slim-rbac/badges/build.png?b=master)](https://scrutinizer-ci.com/g/potievdev/slim-rbac/build-status/master)
</div>

This package helps you to release access control logic via [RBAC](https://en.wikipedia.org/wiki/Role-based_access_control) (Role Based Access Control) technology. The example app you can see [https://github.com/potievdev/slim-rbac-app](https://github.com/potievdev/slim-rbac-app)

## :clipboard: Requirements

- The minimum required PHP version is PHP 5.4.
- Database (MySQL, PostgreSQL, MariaDB)

## :wrench: Installation

### First step

```sh
$ composer require potievdev/slim-rbac "^1.0"
```

### Second step

After installing packages, you should apply database migrations for creating necessary tables. 
There are two cases dependency from your project uses or not uses Doctrine 2 ORM.


##### If you use  Doctrine2

Create a php file with name `sr-config.php` in root directory of project, which returns instance of EntityManager.

```php
<?php
// Include required files, which initializes EntityManager instance.
return EntityManager::getInstance();
```

#### Else

Run next command in root directory or in directory where save configuration files of project

```sh
$ vendor/bin/slim-rbac create
```

Command creates the `sr-config.php` file with next content

```php
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
    'user'     => 'db_user',
    'password' => 'db_pass',
    'dbname'   => 'db_name',
    'port'     => 3306
];

$config = Setup::createAnnotationMetadataConfiguration([], false, null, null, false);

return EntityManager::create($dbParams, $config);
```

Configure database connections params.


After configuration database connection, run next command for applying database migrations

```sh
$ vendor/bin/slim-rbac migrate
```

If all OK you must see similar text


## :hammer: How to use

There are tree major components

- `AuthOptions` - component for saving configurations
- `AuthManager` - component for managing roles and permissions
- `AuthMiddleware` - Slim3 middleware component

### About AuthOptions
Saves configuration values for AuthMiddleware and AuthManager
- `setEntityManager` - sets entityManager instance
- `setsetVariableStorageType` - sets where save information about user. Default value: `AuthOptions::ATTRIBUTE_STORAGE_TYPE` values saved in request attributes.
- `setVariableName` - sets variable name. Default value: `AuthOptions::DEFAULT_VARIABLE_NAME`

### About AuthMiddleware
AuthMiddleware only checks permissions. By default the permission name is same with uri path

```php
$permissionName = $request->getUri()->getPath();
```
For url `https://example.com/write` permission name is `/write`  

When permission denied, middleware returns response with code `403`


### How initialize AuthMiddleware 

1. Create AuthOptions instance and configure it 

```php

$authOptions = new AuthOptions();
// Setting entity manager instance
$authOptions->setEntityManager($entityManager);

```
2. Adding middleware

```php
// $app is instance of Slim application
$app->add(new AuthMiddleware($authOptions));
```

### How initialize  AuthManager
 1. Create AuthOptions instance and configure it 
 
 ```php
 
 $authOptions = new AuthOptions();
 // Setting entity manager instance
 $authOptions->setEntityManager($entityManager);
 
 ```
 2. Create manager instance
 
 ```php
$authManager = new AuthManager($this->authOptions);
 ```
 
Simple example
 ```php

 // Creating edit permission
        $edit = $this->authManager->createPermission('/edit');
        $edit->setDescription('This is edit permission'); // Optional
        $this->authManager->addPermission($edit);

// Creating write permission
        $write = $this->authManager->createPermission('/write');
        $this->authManager->addPermission($write);

// Creating moderator role
        $moderator = $this->authManager->createRole('moderator');
        $moderator->setDescription('This is moderator role'); // Optional
        $this->authManager->addRole($moderator);

// Creating admin role
        $admin = $this->authManager->createRole('admin');
        $this->authManager->addRole($admin);

// Adding permissions to roles
        $this->authManager->addChildPermission($moderator, $edit);
        $this->authManager->addChildPermission($admin, $write);

// Adding child role to role
        $this->authManager->addChildRole($admin, $moderator);

// Assigning roles to users
        $this->authManager->assign($moderator, 1);
        $this->authManager->assign($admin, 2);
 ```

## :crossed_flags: Contribution

## :memo: Licence
MIT License

Copyright (c) 2017 ABDULMALIK ABDULPOTIEV