<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/9
 * Time: 2:14 PM
 */

namespace EasySmartProgram\Support;

use Pimple\Container;
use EasySmartProgram\Support\Cache\ServiceProvider as CacheServiceProvider;
use EasySmartProgram\Support\Config\ServiceProvider as ConfigServiceProvider;
use EasySmartProgram\Support\Log\ServiceProvider as LogServiceProvider;
use EasySmartProgram\Support\Http\ServiceProvider as HttpServiceProvider;

/**
 * Class ServiceContainer
 * @package EasySmartProgram\Support
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 *
 * @property \EasySmartProgram\Support\Cache\Cache $cache
 * @property \Illuminate\Support\Collection $config
 * @property \EasySmartProgram\Support\Log\Logger $logger
 * @property \EasySmartProgram\Support\Http\HttpClient $http_client
 */
class ServiceContainer extends Container
{
    /**
     * @var array
     */
    protected $userConfig = [];

    /**
     * @var array
     */
    protected $providers = [];

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
     * ServiceContainer constructor.
     * @param array $config
     * @param array $values
     */
    public function __construct(array $config = [], array $values = [])
    {
        parent::__construct($values);

        $this->userConfig = $config;
        $this->registerProviders($this->getProviders());
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->userConfig;
    }

    /**
     * Magic get access.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function __get($id)
    {
        return $this->offsetGet($id);
    }

    /**
     * Magic set access.
     *
     * @param string $id
     * @param mixed  $value
     */
    public function __set($id, $value)
    {
        $this->offsetSet($id, $value);
    }

    /**
     * @param string $id
     * @param mixed $value
     * @return $this
     */
    public function rebind(string $id, $value)
    {
        $this->offsetUnset($id);
        $this->offsetSet($id, $value);
        return $this;
    }

    /**
     * Return all providers.
     *
     * @return array
     */
    protected function getProviders()
    {
        return array_merge($this->defaultProviders, $this->providers);
    }

    /**
     * @param array $providers
     */
    protected function registerProviders(array $providers = [])
    {
        foreach ($providers as $provider) {
            parent::register(new $provider());
        }
    }
}