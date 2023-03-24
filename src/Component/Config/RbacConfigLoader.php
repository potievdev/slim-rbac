<?php

namespace Potievdev\SlimRbac\Component\Config;

use Potievdev\SlimRbac\Exception\ConfigNotFoundException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;

class RbacConfigLoader
{
    /**
     * @throws ConfigNotFoundException
     */
    public static function loadConfigs(): array
    {
        $configDirectories = [
            __DIR__ . '/../../../config',
            __DIR__ . '/../../../../../',
            __DIR__ . '/../../../../../config',
        ];

        $fileLocator = new FileLocator($configDirectories);
        $fileName = $fileLocator->locate('sr_config.yaml');

        if ($fileName === null) {
            throw ConfigNotFoundException::configFileNotFound($configDirectories);
        }

        return (new Processor())
            ->processConfiguration(new RbacConfigStructure(), Yaml::parseFile($fileName));
    }

}