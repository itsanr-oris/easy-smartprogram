<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/11
 * Time: 7:22 PM
 */

namespace EasySmartProgram\Support\Cache;

use EasySmartProgram\Support\Cache\Adapter\Factory;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider
 * @package EasySmartProgram\Support\Cache
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
        !isset($app['cache_adapter']) && $app['cache_adapter'] = function () {
            return new Factory();
        };

        !isset($app['cache']) && $app['cache'] = function ($app) {
            return new Cache($app['cache_adapter'], $app->config['cache']);
        };
    }
}