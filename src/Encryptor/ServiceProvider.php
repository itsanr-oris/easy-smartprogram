<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/1
 * Time: 9:56 AM
 */

namespace EasySmartProgram\Encryptor;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider
 * @package EasySmartProgram\Encryptor
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
        !isset($app['encryptor']) && $app['encryptor'] = function ($app) {
            return new Encryptor($app);
        };
    }
}