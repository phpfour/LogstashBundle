<?php

/**
 * This file is part of the LogstashBundle package.
 *
 * (c) Mohammad Emran Hasan <http://emranhasan.com/>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Loosemonkies\Bundle\LogstashBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This class handles the bundle specific configuration
 *
 * @author Mohammad Emran Hasan <emranhasan@gmail.com>
 */
class LoosemonkiesLogstashExtension extends Extension
{
    /**
     * Handles the loosemonkies_logstash configuration.
     *
     * @param array            $configs   The configurations being loaded
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if (isset($config['redis'])) {
            $settings = $config['redis'];
            foreach ($settings as $key => $value) {
                $container->setParameter('lm.logstash.redis.' . $key, $value);
            }
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
