<?php

/**
 * This file is part of the LogstashBundle package.
 *
 * (c) Mohammad Emran Hasan <http://emranhasan.com/>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Loosemonkies\Bundle\LogstashBundle\Input;

use Psr\Log\LoggerInterface;
use Symfony\Bridge\Monolog\Logger;
use Monolog\Handler\RedisHandler;
use Monolog\Formatter\LogstashFormatter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * The custom logger class that wraps the monolog logger
 * to work with redis based logstash configuration.
 *
 * @author Mohammad Emran Hasan <emranhasan@gmail.com>
 */
class RedisInput implements LoggerInterface
{
    /** @var Logger */
    protected $logger;

    public function __construct(ContainerInterface $container)
    {
        $host = $container->getParameter('lm.logstash.redis.host');
        $port = $container->getParameter('lm.logstash.redis.port');
        $list = $container->getParameter('lm.logstash.redis.list');
        $name = $container->getParameter('lm.logstash.redis.name');

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

        $this->logger = $container->get('logger');
        $this->logger->pushHandler($redisHandler);
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function emergency($message, array $context = array())
    {
        $this->logger->emergency($message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function alert($message, array $context = array())
    {
        $this->logger->alert($message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function critical($message, array $context = array())
    {
        $this->logger->critical($message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function error($message, array $context = array())
    {
        $this->logger->error($message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function warning($message, array $context = array())
    {
        $this->logger->warning($message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function notice($message, array $context = array())
    {
        $this->logger->notice($message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function info($message, array $context = array())
    {
        $this->logger->info($message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function debug($message, array $context = array())
    {
        $this->logger->debug($message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        $this->logger->log($level, $message, $context);
    }
}