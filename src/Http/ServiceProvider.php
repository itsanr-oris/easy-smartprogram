<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/18
 * Time: 10:54 AM
 */

namespace EasySmartProgram\Http;

use Pimple\Container;

class ServiceProvider extends \EasySmartProgram\Support\Http\ServiceProvider
{
    public function register(Container $app)
    {
        !isset($app['http_client']) && $app['http_client'] = function ($app) {
            $client = new HttpClient($app);

            $this->addLogMiddleware($app, $client);
            $this->addRetryMiddleware($app, $client);

            return $client;
        };
    }
}