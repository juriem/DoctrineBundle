<?php

namespace Gizlab\Bundle\DoctrineBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('gizlab_doctrine');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode->append($this->createDiscriminatorConfig());


        return $treeBuilder;
    }

    protected function createDiscriminatorConfig()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('discriminator_listener');

        $rootNode->children()
            ->arrayNode('classes')->isRequired()
                    ->prototype('array')
                        ->beforeNormalization()
                            ->ifString()
                            ->then(function($v) { return array('class' => $v); })
                        ->end()
                        ->children()
                            ->scalarNode('class')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $rootNode;
    }

}
