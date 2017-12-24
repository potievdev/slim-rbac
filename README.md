# Slim3 RBAC Middleware

[![Build Status](https://travis-ci.org/potievdev/slim-rbac.svg?branch=master)](https://travis-ci.org/potievdev/slim-rbac)
[![codecov](https://codecov.io/gh/potievdev/slim-rbac/branch/master/graph/badge.svg)](https://codecov.io/gh/potievdev/slim-rbac)
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Fpotievdev%2Fslim-rbac.svg?type=shield)](https://app.fossa.io/projects/git%2Bgithub.com%2Fpotievdev%2Fslim-rbac?ref=badge_shield)

This package helps you to release access control logic via [RBAC](https://en.wikipedia.org/wiki/Role-based_access_control) (Role Based Access Control) technology.

## Requirements
---

- The minimum required PHP version is PHP 5.4.

## Installation
---

### First step

```sh
$ composer require potievdev/slim-rbac "^1.0"
```

### Second step

After installing packages, you should apply database migrations for creating necessary tables. 
There are two cases dependency from your project uses or not uses Doctrine 2 ORM.

##### If you use  Doctrine2

1. Create a php file, which returns instance of EntityManager.

```php
...
return $entityManager;
```

2. Run applying migrations command

```sh
$ vendor/bin/slim-rbac migrate -c PATH_TO_ENTITY_MANAGER_FILE
```

#### Else

1. Run next command in root directory or in directory where save configuration files of project

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
    'user'     => 'root',
    'password' => '',
    'dbname'   => 'test',
    'port'     => 3306
];

$config = Setup::createAnnotationMetadataConfiguration([], false, null, null, false);

return EntityManager::create($dbParams, $config);
```

Configure database connections params and run applying migrations command

```sh
$ vendor/bin/slim-rbac migrate
```

If all OK you must see similar screen

![alt text](https://3.downloader.disk.yandex.ru/disk/782e11e8921bcfee4ea27a72435df7daed1143be642dc7d16f0e56f551e82c71/5a3db17f/YDaZG483KqpGyRGM7PeuU7xeykrU0TVIUy_eD6Cnj68YRmNeDwNIGK0sEtC9132Xv_8Lm7GO59c_KhyTJ4s3Cw%3D%3D?uid=0&filename=2017-12-23_00-12-42.png&disposition=inline&hash=&limit=0&content_type=image%2Fpng&fsize=14626&hid=f6d1fe369a897a4c23f44ef036e2d5a6&media_type=image&tknv=v2&etag=3d96d11be88f0d4c592264e02c6ccb50)

## How to use
---

There are tree major components

- `AuthOptions` - component for saving configurations
- `AuthManager` - component for managing roles and permissions
- `AuthMiddleware` - Slim3 middleware component

### How initialize middleware 

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
### How initialize manager
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
        $edit = $this->authManager->createPermission('edit');
        $this->authManager->addPermission($edit);
// Creating write permission
        $write = $this->authManager->createPermission('write');
        $this->authManager->addPermission($write);
// Creating moderator role
        $moderator = $this->authManager->createRole('moderator');
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

## Contribution
---

## Licience
---
MIT License

Copyright (c) 2017 ABDULMALIK ABDULPOTIEV

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

## License
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Fpotievdev%2Fslim-rbac.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2Fpotievdev%2Fslim-rbac?ref=badge_large)