<?php

declare(strict_types=1);

namespace Boldy\SyliusExportPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('boldy_sylius_export');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->fixXmlConfig('gabarit')
            ->children()
                ->arrayNode('gabarits')
                    ->arrayPrototype()
                        ->children()
                            ->arrayNode('headers')
                                ->scalarPrototype()->end()
                            ->end()
                            ->arrayNode('resource_keys')
                                ->scalarPrototype()->end()
                            ->end()
                            ->arrayNode('formats')
                                ->isRequired()
                                ->arrayPrototype()
                                    ->children()
                                        ->scalarNode('exporter')
                                            ->isRequired()
                                            ->cannotBeEmpty()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

            return $treeBuilder;
    }
}
