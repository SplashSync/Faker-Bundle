<?php

namespace Splash\Connectors\FakerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('splash_faker');

        $rootNode
            ->children()
                
                //====================================================================//
                // Fakes Objects to Create
                //====================================================================//
                
                ->arrayNode('objects')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('id')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('name')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('format')->isRequired()->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()
                
            ->end()
        ;


        return $treeBuilder;
    }
}
