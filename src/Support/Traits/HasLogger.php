<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/10
 * Time: 4:24 PM
 */

namespace EasySmartProgram\Support\Traits;

use EasySmartProgram\Support\Log\Driver\Factory;
use EasySmartProgram\Support\Log\Logger;
use EasySmartProgram\Support\ServiceContainer;

/**
 * Trait HasLogger
 * @package EasySmartProgram\Support\Traits
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
trait HasLogger
{
    /**
     * @return Logger|mixed
     * @throws \EasySmartProgram\Support\Exception\InvalidConfigException
     */
    public function logger()
    {
        if (method_exists($this, 'app')) {
            $app = $this->app();
            if (!empty($app) && $app instanceof ServiceContainer && isset($app['logger'])) {
                return $app['logger'];
            }
        }

        return new Logger(new Factory());
    }
}