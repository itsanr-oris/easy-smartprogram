<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/9
 * Time: 2:13 PM
 */

namespace EasySmartProgram\Support\Log;

use EasySmartProgram\Support\Log\Driver\Factory;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider
 * @package EasySmartProgram\Support\Log
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
        !isset($app['logger_driver']) && $app['logger_driver'] = function () {
            return new Factory();
        };

        !isset($app['logger']) && $app['logger'] = function ($app) {
            return new Logger($app['logger_driver'], $app->config['log']);
        };
    }
}