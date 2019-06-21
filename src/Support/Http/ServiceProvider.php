<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/9
 * Time: 2:15 PM
 */

namespace EasySmartProgram\Support\Http;

use EasySmartProgram\Support\Http\Middleware\LogMiddleware;
use EasySmartProgram\Support\Http\Middleware\RetryMiddleware;
use EasySmartProgram\Support\Log\Driver\Factory;
use EasySmartProgram\Support\Log\Logger;
use EasySmartProgram\Support\ServiceContainer;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider
 * @package EasySmartProgram\Support\Http
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $app
     */
    public function register(Container $app)
    {
        !isset($app['http_client']) && $app['http_client'] = function ($app) {
            $client = new HttpClient($app->config['http_client']);

            $this->addLogMiddleware($app, $client);
            $this->addRetryMiddleware($app, $client);

            return $client;
        };
    }

    /**
     * @param ServiceContainer $app
     * @param HttpClient       $client
     * @throws \EasySmartProgram\Support\Exception\InvalidConfigException
     */
    protected function addLogMiddleware(ServiceContainer $app, HttpClient $client)
    {
        $logger = $app['logger'] ?? new Logger(new Factory());
        $client->pushMiddleware(new LogMiddleware($logger, $app->config['http_client'] ?? []));
    }

    /**
     * @param ServiceContainer $app
     * @param HttpClient       $client
     */
    protected function addRetryMiddleware(ServiceContainer $app, HttpClient $client)
    {
        $client->pushMiddleware(new RetryMiddleware($app->config['http_client'] ?? []));
    }
}