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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle
 *
 * @author Mohammad Emran Hasan <emranhasan@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('loosemonkies_logstash');

        $rootNode
            ->children()
                ->arrayNode('redis')
                    ->children()
                        ->scalarNode('host')->defaultValue('localhost')->end()
                        ->scalarNode('port')->defaultValue('6379')->end()
                        ->scalarNode('list')->defaultValue('logstash')->end()
                        ->scalarNode('name')->defaultValue('app')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
