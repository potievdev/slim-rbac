<?php

namespace Potievdev\SlimRbac\Component;

use Potievdev\SlimRbac\Component\Config\RbacConfig;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class RbacContainer
 * @package Potievdev\SlimRbac\Component
 */
class RbacContainer
{
    /** @var  ContainerBuilder $containerBuilder */
    protected $containerBuilder;

    /**
     * RbacContainer constructor.
     */
    public function __construct(?RbacConfig $rbacConfig = null)
    {
        $this->containerBuilder = new ContainerBuilder();

        if (isset($rbacConfig)) {
            $this->containerBuilder->set('rbacConfig', $rbacConfig);
        }

        $loader = new YamlFileLoader($this->containerBuilder, new FileLocator(__DIR__));
        $loader->load('services.yaml');
    }

    public function getRbacMiddleware(): RbacMiddleware
    {
        return $this->containerBuilder->get('middleware');
    }

    public function getRbacManager(): RbacManager
    {
        return $this->containerBuilder->get('manager');
    }

    public function getInnerContainer(): ContainerBuilder
    {
        return $this->containerBuilder;
    }
}
