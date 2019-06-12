<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/5
 * Time: 10:31 AM
 */

namespace EasySmartProgram\TemplateMessage;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider
 * @package EasySmartProgram\TemplateMessage
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
        !isset($app['template_message']) && $app['template_message'] = function ($app) {
            return new Client($app);
        };
    }
}