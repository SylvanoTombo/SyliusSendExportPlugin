<?php

declare(strict_types=1);

namespace Boldy\SyliusExportPlugin\DependencyInjection;

use Boldy\SyliusExportPlugin\Registry\Gabarit;
use Sylius\Bundle\UiBundle\Registry\TemplateBlockRegistryInterface;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

final class BoldySyliusExportExtension extends Extension
{
    public function load(array $config, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $config);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('services.yaml');

        $this->loadGabarits($config['gabarits'], $container);

    }

    public function getConfiguration(array $config, ContainerBuilder $container): ConfigurationInterface
    {
        return new Configuration();
    }

    private function loadGabarits(array $gabarits, ContainerBuilder $container)
    {
        $serviceId = 'boldy.gabarits_registry';
        $gabaritKeys = [];

        if (!$container->has($serviceId)) return;

        $gabaritsRegistry = $container->findDefinition($serviceId);

        foreach ($gabarits as $id => $gabarit)
        {
            $gabaritService = new Definition(Gabarit::class);
            $gabaritService->setArgument(0, $gabarit['headers']);
            $gabaritService->setArgument(1, $gabarit['resource_keys']);

            foreach ($gabarit['formats'] as $format => $exporter)
            {
                $gabaritService->addMethodCall('addExporter', [$format, new Reference($exporter['exporter'])]);
                $gabaritKeys[$id]['format'] = array_keys($gabarit['formats']);
            }

            $gabaritsRegistry->addMethodCall('register', [$id, $gabaritService]);
        }

        $container->setParameter('boldy.gabarits', $gabaritKeys);
    }

}
