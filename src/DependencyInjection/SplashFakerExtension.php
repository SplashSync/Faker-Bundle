<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) Splash Sync <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 *  @author Bernard Paquier <contact@splashsync.com>
 */

namespace Splash\Connectors\FakerBundle\DependencyInjection;

use Splash\Connectors\FakerBundle\Objects\Generic;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
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
        foreach ($config['objects'] as $Object) {
            $container
                ->register(
                    'splash.connector.faker.object.'.$Object['id'],
                    Generic::class
                )
                ->addTag('splash.standalone.object')
                ->addMethodCall('setConfiguration', [$Object['id'], $Object['name'], $Object['format']])
                ->setPublic(true)
                ->setAutowired(true)
                    ;
        }
    }
}
