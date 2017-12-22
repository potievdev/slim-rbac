# Slim3 RBAC Middleware


This package helps you to release access control logic via [RBAC](https://en.wikipedia.org/wiki/Role-based_access_control) (Role Based Access Control) technology.

## Requirements
___
- The minimum required PHP version is PHP 5.4.

## Installation
___
### First step
Run next command.
```sh
$ composer require potievdev/slim-rbac "^1.0"
```
### Second step
After installing packages, you should apply database migrations for creating necessary tables. There are two ways dependently from you use or not use Doctrine2 ORM in your project.

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
<p align="center">
    <img src="https://yadi.sk/i/_FCfUL5J3QsQC7" alt="Slim RBAC migrations applying screenshot" />
</p>

## How to use
___

## Contribution
___

## Licience
___
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

