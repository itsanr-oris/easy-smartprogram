<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/12
 * Time: 10:41 PM
 */

namespace EasySmartProgram\Support\Traits;

use EasySmartProgram\Support\Cache\Adapter\Factory;
use EasySmartProgram\Support\Cache\Cache;
use EasySmartProgram\Support\ServiceContainer;

/**
 * Trait HasCache
 * @package EasySmartProgram\Support\Traits
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
trait HasCache
{
    /**
     * @return Cache|mixed
     * @throws \EasySmartProgram\Support\Exception\InvalidConfigException
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     */
    public function cache()
    {
        if (method_exists($this, 'app')) {
            $app = $this->app();
            if (!empty($app) && $app instanceof ServiceContainer && isset($app['cache'])) {
                return $app['cache'];
            }
        }

        return new Cache(
            new Factory(),
            [
                'default' => 'file',
                'drivers' => [
                    'file' => ['path' => sys_get_temp_dir() . '/cache/']
                ]
            ]
        );
    }
}