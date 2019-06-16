<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/10
 * Time: 3:57 PM
 */

namespace EasySmartProgram\Support\Http\Middleware;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * Class RetryMiddleware
 * @package EasySmartProgram\Support\Http\Middleware
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class RetryMiddleware implements MiddlewareInterface
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * RetryMiddleware constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @return int|mixed
     */
    protected function retries()
    {
        return $this->config['max_retries'] ?? 1;
    }

    /**
     * @return int|mixed
     */
    protected function delay()
    {
        return function () {
            return $this->config['retry_delay'] ?? 500;
        };
    }

    /**
     * @return \Closure
     */
    protected function decider()
    {
        return function (
            $retries,
            Request $request,
            Response $response = null,
            RequestException $exception = null
        ) {
            if ($retries >= $this->retries()) {
                return false;
            }

            if ($exception instanceof ConnectException
                || $exception instanceof ServerException) {
                return true;
            }

            if ($response && $response->getStatusCode() >= 500) {
                return true;
            }

            return false;
        };
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'retry';
    }

    /**
     * @return callable
     */
    public function callable(): callable
    {
        return Middleware::retry($this->decider(), $this->delay());
    }
}