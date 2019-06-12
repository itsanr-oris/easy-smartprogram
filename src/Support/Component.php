<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/9
 * Time: 2:14 PM
 */

namespace EasySmartProgram\Support;

use EasySmartProgram\Support\Traits\HasCache;
use EasySmartProgram\Support\Traits\HasHttpClient;
use EasySmartProgram\Support\Traits\HasLogger;

/**
 * Class Component
 * @package EasySmartProgram\Support
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class Component
{
    use HasLogger, HasHttpClient, HasCache;

    /**
     * @var ServiceContainer
     */
    protected $app;

    /**
     * Component constructor.
     * @param ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }

    /**
     * @return ServiceContainer
     */
    public function app() : ServiceContainer
    {
        return $this->app;
    }
}