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

        !isset($app['swan_id']) && $app['swan_id'] = function ($app) {
            return new SwanId($app);
        };
    }
}