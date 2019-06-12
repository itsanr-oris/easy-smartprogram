<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/10
 * Time: 4:49 PM
 */

namespace EasySmartProgram\Support\Traits;

use EasySmartProgram\Support\Http\HttpClient;
use EasySmartProgram\Support\ServiceContainer;

/**
 * Trait HasHttpClient
 * @package EasySmartProgram\Support\Traits
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
trait HasHttpClient
{
    /**
     * @return HttpClient|mixed
     */
    public function http()
    {
        if (method_exists($this, 'app')) {
            $app = $this->app();
            if (!empty($app) && $app instanceof ServiceContainer && isset($app['http_client'])) {
                return $app['http_client'];
            }
        }

        return new HttpClient();
    }
}