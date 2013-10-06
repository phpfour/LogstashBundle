<?php

/**
 * This file is part of the LogstashBundle package.
 *
 * (c) Mohammad Emran Hasan <http://emranhasan.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Emran\Bundle\LogstashBundle\EventListener;

use Monolog\Handler\RedisHandler;
use Monolog\Formatter\LogstashFormatter;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * Event that adds the logstash handler to the logger service
 *
 * @author Mohammad Emran Hasan <phpfour@gmail.com>
 */
class LoggerListener
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Fired when controller has been setup
     *
     * @param FilterControllerEvent $event
     *
     * @throws InvalidConfigurationException
     * @return mixed
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $host = $this->container->getParameter('logstash.redis.host');
        $port = $this->container->getParameter('logstash.redis.port');
        $list = $this->container->getParameter('logstash.redis.list');
        $name = $this->container->getParameter('logstash.redis.name');

        if (class_exists('\Redis')) {
            $redis = new \Redis();
            $redis->connect($host, $port);
        } elseif (class_exists('\Predis\Client')) {
            $redis = new \Predis\Client("tcp://$host:$port");
        } else {
            throw new InvalidConfigurationException('Predis\Client class or Redis extension required.');
        }

        $redisHandler = new RedisHandler($redis, $list);
        $redisHandler->setFormatter(new LogstashFormatter($name));

        $logger = $this->container->get('logger');
        $logger->pushHandler($redisHandler);
    }
}