<?php /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/5/16
 * Time: 3:20 PM
 */

namespace EasySmartProgram;


use EasySmartProgram\Support\ServiceContainer;

/**
 * Class Application
 *
 * @package EasySmartProgram
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 *
 * @property \EasySmartProgram\Auth\AccessToken       $access_token
 * @property \EasySmartProgram\Auth\Auth              $auth
 * @property \EasySmartProgram\Encryptor\Encryptor    $encryptor
 * @property \EasySmartProgram\TemplateMessage\Client $template_message
 * @property \EasySmartProgram\Auth\SwanId            $swan_id
 */
class Application extends ServiceContainer
{
    /**
     * 启用的组件
     *
     * @var array
     */
    protected $providers = [
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
        $this->userConfig['http']['timeout'] = $this->userConfig['http']['timeout'] ?? 30;
        $this->userConfig['http']['base_uri'] = 'https://openapi.baidu.com/rest/2.0/smartapp/';

        return $this->userConfig;
    }
}