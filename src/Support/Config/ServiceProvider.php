<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/12
 * Time: 2:52 PM
 */

namespace EasySmartProgram\Support\Config;

use EasySmartProgram\Support\Collection\Collection;
use EasySmartProgram\Support\ServiceContainer;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider
 * @package EasySmartProgram\Support\Config
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
        !isset($app['config']) && $app['config'] = function (ServiceContainer $app) {
            return new Collection($app->getConfig());
        };
    }
}