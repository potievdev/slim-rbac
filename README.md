<div align="center">
    <h1> Slim4 RBAC Middleware </h1>
</div>

[![Build Status](https://travis-ci.org/potievdev/slim-rbac.svg?branch=master)](https://travis-ci.org/potievdev/slim-rbac)
[![codecov](https://codecov.io/gh/potievdev/slim-rbac/branch/master/graph/badge.svg)](https://codecov.io/gh/potievdev/slim-rbac)
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Fpotievdev%2Fslim-rbac.svg?type=shield)](https://app.fossa.io/projects/git%2Bgithub.com%2Fpotievdev%2Fslim-rbac?ref=badge_shield)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/potievdev/slim-rbac/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/potievdev/slim-rbac/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/potievdev/slim-rbac/badges/build.png?b=master)](https://scrutinizer-ci.com/g/potievdev/slim-rbac/build-status/master)
[![Total Downloads](https://poser.pugx.org/potievdev/slim-rbac/downloads)](https://packagist.org/packages/potievdev/slim-rbac)

This package helps you to release access control logic via [RBAC](https://en.wikipedia.org/wiki/Role-based_access_control) (Role Based Access Control) technology. The example app you can see [https://github.com/potievdev/slim-rbac-app](https://github.com/potievdev/slim-rbac-app)

## :clipboard: Requirements

- The minimum required PHP version is PHP 7.3.
- Supported database engines 
  * MySQL
  * PostgreSQL
  * MariaDB

## :wrench: Installation

### First step

```sh
$ composer require potievdev/slim-rbac "^2.0"
```

### Second step

After installing packages, you should apply database migrations for
creating necessary tables. There are two cases dependency from your
project uses or not uses Doctrine 2 ORM.


##### If you use  Doctrine2

Create a php file with name `sr-config.php` in root directory of project,
 which returns instance of EntityManager.

```php
<?php
// Include required files, which initializes EntityManager instance.
return EntityManager::getInstance();
```

#### Else

Run next command in root directory or in directory where saved
configuration files of project

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

If all `OK` you must see list of applied migrations, which prints `phinx`


## :hammer: How to use

There are tree major components

- `RbacManagerOptions` - context for saving `RbacManager` configurations.
- `RbacManager` - component for managing roles and permissions.
- `RbacMiddleware` - Slim4 middleware component.

### About RbacManagerOptions
Container for saving configuration params for RbacMiddleware and RbacManager.
- `setUserIdStorageType` - sets where we will save current user identifier.
In below given table you can see list of storage types
- `setUserIdFieldName` - sets field name, where we save current user identifier.
Default value: `RbacManagerOptions::DEFAULT_USER_ID_FIELD_NAME` which equals to `userId`

| Storage Type | Description | Default |
| ------------ | ----------- | ------- |
| `RbacManagerOptions::ATTRIBUTE_STORAGE_TYPE` | Middleware gets user id from attributes | Yes |
| `RbacManagerOptions::HEADER_STORAGE_TYPE` | Middleware gets user id from header | No |
| `RbacManagerOptions::COOKIE_STORAGE_TYPE` | Middleware gets user id cookie | No |


### About RbacMiddleware
RbacMiddleware checks permissions.
By default, the permission name is same with uri path.

```php
$permissionName = $request->getUri()->getPath();
```
For url `https://example.com/write` permission name is `/write`.  

When permission denied, middleware returns response with code `403`.


### How initialize RbacMiddleware 

1. Create RbacManagerOptions instance and configure it 

```php

$rbacManagerOptions = new RbacManagerOptions($entityManager);

```
2. Adding middleware

```php
// $app is instance of Slim application
$app->add(new RbacMiddleware($rbacManagerOptions));
```

### How initialize RbacManager
 1. Create RbacManagerOptions instance and configure it 
 
 ```php
  $rbacManagerOptions = new RbacManagerOptions($entityManager);
  ```
 2. Create manager instance
 
 ```php
$rbacManager = new RbacManager($this->rbacManagerOptions);
 ```
 
Simple example
 ```php

 // Creating edit permission
        $edit = $this->rbacManager->createPermission('/edit', 'This is edit permission');

// Creating write permission
        $write = $this->rbacManager->createPermission('/write');

// Creating moderator role
        $moderator = $this->rbacManager->createRole('moderator', 'This is moderator role');

// Creating admin role
        $admin = $this->rbacManager->createRole('admin');

// Adding permissions to roles
        $this->rbacManager->attachPermission($moderator, $edit);
        $this->rbacManager->attachPermission($admin, $write);

// Adding child role to role
        $this->rbacManager->attachChildRole($admin, $moderator);

// Assigning roles to users
        $this->rbacManager->assign($moderator, 1);
        $this->rbacManager->assign($admin, 2);
 ```

## :crossed_flags: Contribution
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## :memo: Licence
MIT License