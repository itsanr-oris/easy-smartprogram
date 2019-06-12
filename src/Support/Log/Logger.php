<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/12
 * Time: 4:27 PM
 */

namespace EasySmartProgram\Support\Log;

use Psr\Log\LoggerInterface;
use EasySmartProgram\Support\Log\Driver\Factory as DriverFactory;

/**
 * Class Logger
 * @package EasySmartProgram\Support\Log
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class Logger implements LoggerInterface
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var LoggerInterface
     */
    protected $driver;

    /**
     * @var DriverFactory
     */
    protected $driverFactory;

    /**
     * Logger constructor.
     * @param DriverFactory $factory
     * @param array         $config
     */
    public function __construct(DriverFactory $factory, array $config = [])
    {
        $this->config = $config;
        $this->initStackConfig()->setDriverFactory($factory);
    }

    /**
     * @return $this
     */
    protected function initStackConfig()
    {
        if (isset($this->config['channel']['stack'])) {
            $this->config['channel']['stack']['total_channels'] = $this->config['channel'];
        }
        return $this;
    }

    /**
     * @param DriverFactory $factory
     * @return $this
     */
    public function setDriverFactory(DriverFactory $factory)
    {
        $this->driverFactory = $factory;
        return $this;
    }

    /**
     * @return DriverFactory
     */
    public function getDriverFactory() : DriverFactory
    {
        return $this->driverFactory;
    }

    /**
     * @param string $channel
     * @return $this
     */
    public function channel(string $channel)
    {
        $this->config['default'] = $channel;
        $this->driver = null;
        return $this;
    }

    /**
     * @param array $channels
     * @return $this
     */
    public function stack(array $channels)
    {
        $this->config['default'] = 'stack';
        $this->config['channel']['stack']['channels'] = $channels;
        $this->initStackConfig();
        $this->driver = null;
        return $this;
    }

    /**
     * @return LoggerInterface
     */
    public function driver() : LoggerInterface
    {
        if (!$this->driver instanceof LoggerInterface) {
            $channel = $this->config['default'] ?? null;
            $config = $this->config['channels'][$channel] ?? [];
            $this->driver = $this->getDriverFactory()->make($channel, $config);
        }

        return $this->driver;
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public function emergency($message, array $context = array())
    {
        $this->driver()->emergency($message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public function alert($message, array $context = array())
    {
        $this->driver()->alert($message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public function critical($message, array $context = array())
    {
        $this->driver()->critical($message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public function error($message, array $context = array())
    {
        $this->driver()->error($message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public function warning($message, array $context = array())
    {
        $this->driver()->warning($message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public function notice($message, array $context = array())
    {
        $this->driver()->notice($message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public function info($message, array $context = array())
    {
        $this->driver()->info($message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public function debug($message, array $context = array())
    {
        $this->driver()->debug($message, $context);
    }

    /**
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     */
    public function log($level, $message, array $context = array())
    {
        $this->driver()->log($level, $message, $context);
    }
}