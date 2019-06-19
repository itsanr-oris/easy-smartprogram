<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/18
 * Time: 4:49 PM
 */

namespace EasySmartProgram\Resource;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider
 * @package EasySmartProgram\Resource
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
        !isset($app['resource']) && $app['resource'] = function ($app) {
            return new Resource($app);
        };

        !isset($app['site_map']) && $app['site_map'] = function ($app) {
            return new SiteMap($app);
        };
    }
}