<?php

namespace Potievdev\SlimRbac\Component\Config;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Rbac configuration structure.
 */
class RbacConfigStructure implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('rbac');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('database')
                    ->children()
                        ->enumNode('driver')
                            ->values(['pdo_mysql', 'pdo_postgres'])
                            ->isRequired()
                        ->end()
                        ->scalarNode('host')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('user')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('password')
                            ->cannotBeEmpty()
                        ->end()
                        ->integerNode('port')
                            ->isRequired()
                        ->end()
                        ->scalarNode('dbname')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('charset')
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('userId')
                    ->children()
                        ->scalarNode('fieldName')
                            ->cannotBeEmpty()
                        ->end()
                        ->enumNode('resourceType')
                            ->values(['attribute', 'header', 'cookie'])
                            ->isRequired()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}