<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/1
 * Time: 9:42 AM
 */

namespace EasySmartProgram\Auth;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider
 * @package EasySmartProgram\Auth
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
        !isset($app['access_token']) && $app['access_token'] = function ($app) {
            return new AccessToken($app);
        };

        !isset($app['auth']) && $app['auth'] = function ($app) {
            return new Auth($app);
        };

        isset($app['http_client']) && $this->registerAccessTokenMiddleware($app);
    }

    /**
     * @param $app
     */
    protected function registerAccessTokenMiddleware($app)
    {
        $app->http_client->pushMiddleware(new AccessTokenMiddleware());
    }
}