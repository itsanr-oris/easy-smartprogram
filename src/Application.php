<?php /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/5/16
 * Time: 3:20 PM
 */

namespace EasySmartProgram;


use EasySmartProgram\Support\ServiceContainer;
use EasySmartProgram\Support\Cache\ServiceProvider as CacheServiceProvider;
use EasySmartProgram\Support\Config\ServiceProvider as ConfigServiceProvider;
use EasySmartProgram\Support\Log\ServiceProvider as LogServiceProvider;
use EasySmartProgram\Http\ServiceProvider as HttpServiceProvider;

/**
 * Class Application
 *
 * @package EasySmartProgram
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 *
 * @property \EasySmartProgram\Auth\AccessToken        $access_token
 * @property \EasySmartProgram\Auth\Auth               $auth
 * @property \EasySmartProgram\Encryptor\Encryptor     $encryptor
 * @property \EasySmartProgram\TemplateMessage\Manager $template_message
 * @property \EasySmartProgram\Auth\SwanId             $swan_id
 */
class Application extends ServiceContainer
{
    /**
     * @var array
     */
    protected $defaultProviders = [
        ConfigServiceProvider::class,
        CacheServiceProvider::class,
        LogServiceProvider::class,
        HttpServiceProvider::class,
    ];

    /**
     * 启用的组件
     *
     * @var array
     */
    protected $providers = [
        \EasySmartProgram\Http\ServiceProvider::class,
        \EasySmartProgram\Encryptor\ServiceProvider::class,
        \EasySmartProgram\Auth\ServiceProvider::class,
        \EasySmartProgram\TemplateMessage\ServiceProvider::class,
    ];

    /**
     * 重写配置信息
     *
     * @return array
     */
    public function getConfig()
    {
        $this->userConfig['http_client']['timeout'] = $this->userConfig['http_client']['timeout'] ?? 30;
        $this->userConfig['http_client']['base_uri'] = 'https://openapi.baidu.com/rest/2.0/smartapp/';

        return $this->userConfig;
    }
}