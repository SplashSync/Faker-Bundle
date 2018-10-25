<?php

namespace Splash\Connectors\FakerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

use Splash\Connectors\FakerBundle\Objects\Generic;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SplashFakerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('splash_faker', $config);
        
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        
        //====================================================================//
        // Add Splash Standalone Objects Service to Container
        foreach ($config["objects"] as $Object) {
            $container
                ->register(
                    'splash.connector.faker.object.' . $Object["id"],
                    Generic::class
                )
                ->addTag('splash.standalone.object')
                ->addMethodCall('setConfiguration', array($Object["id"], $Object["name"], $Object["format"]))
                ->setPublic(true)
                ->setAutowired(true)
                    ;
        }
    }
}
