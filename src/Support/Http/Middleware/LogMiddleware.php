<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/10
 * Time: 3:57 PM
 */

namespace EasySmartProgram\Support\Http\Middleware;

use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Class LogMiddleware
 * @package EasySmartProgram\Support\Http\Middleware
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class LogMiddleware implements MiddlewareInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * LogMiddleware constructor.
     * @param LoggerInterface|null $logger
     * @param array                $config
     */
    public function __construct(LoggerInterface $logger, array $config = [])
    {
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * @return LoggerInterface
     */
    protected function logger() : LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @return mixed|string
     */
    protected function format()
    {
        return $this->config['log_template'] ?? MessageFormatter::DEBUG;
    }

    /**
     * @return mixed|string
     */
    protected function level()
    {
        return $this->config['log_level'] ?? LogLevel::INFO;
    }

    /**
     * @return string
     */
    public function name() : string
    {
        return 'log';
    }

    /**
     * @return callable
     */
    public function callable() : callable
    {
        return Middleware::log($this->logger(), new MessageFormatter($this->format()), $this->level());
    }
}