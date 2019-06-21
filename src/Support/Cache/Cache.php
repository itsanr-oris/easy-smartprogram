<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/12
 * Time: 9:28 AM
 */

namespace EasySmartProgram\Support\Cache;

use EasySmartProgram\Support\Cache\Adapter\Factory;
use EasySmartProgram\Support\Exception\InvalidConfigException;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Class CacheManager
 * @package EasySmartProgram\Support\Cache
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class Cache
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var CacheItemPoolInterface
     */
    protected $driver;

    /**
     * Cache constructor.
     * @param Factory $factory
     * @param array   $config
     * @throws InvalidConfigException
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     */
    public function __construct(Factory $factory, array $config = [])
    {
        $this->factory = $factory;
        $this->config = $config;

        $this->driver($this->config['default']);
    }

    /**
     * @param string|null $driver
     * @return $this
     * @throws InvalidConfigException
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     */
    public function driver(string $driver = null)
    {
        if ($driver && !isset($this->config['drivers'][$driver])) {
            throw new InvalidConfigException('No cache driver configuration was found!');
        }

        $driver = $driver ?? $this->config['default'];
        $this->driver = $this->factory->make($driver, $this->getConfig($driver));
        return $this;
    }

    /**
     * @return CacheItemPoolInterface
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param string|null $driver
     * @return array
     * @throws InvalidConfigException
     */
    public function getConfig(string $driver = null)
    {
        if ($driver && !isset($this->config['drivers'][$driver])) {
            throw new InvalidConfigException('No cache driver configuration was found!');
        }

        if ($driver === null) {
            return $this->config;
        }

        $commonConfig = $this->config;
        unset($commonConfig['drivers']);
        return array_merge($this->config['drivers'][$driver], $commonConfig);
    }

    /**
     * @param      $key
     * @param      $value
     * @param null $ttl
     * @return $this
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function put($key, $value, $ttl = null)
    {
        $ttl = $ttl ?? $this->config['life_time'];
        $item = $this->driver->getItem($key)->set($value)->expiresAfter($ttl);
        $this->driver->save($item);
        return $this;
    }

    /**
     * @param      $values
     * @param null $ttl
     * @return $this
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function putMany($values, $ttl = null)
    {
        foreach ($values as $key => $value) {
            $this->put($key, $value, $ttl);
        }

        return $this;
    }

    /**
     * @param      $key
     * @param      $value
     * @param null $ttl
     * @return Cache
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function set($key, $value, $ttl = null)
    {
        return $this->put($key, $value, $ttl);
    }

    /**
     * @param $key
     * @return mixed|null
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function get($key)
    {
        $item = $this->driver->getItem($key);
        return $item->isHit() ? $item->get() : null;
    }

    /**
     * @param          $key
     * @param          $ttl
     * @param \Closure $callback
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function remember($key, $ttl, \Closure $callback)
    {
        $value = $this->get($key);

        if (!is_null($value)) {
            return $value;
        }

        $this->put($key, $value = $callback(), $ttl);

        return $value;
    }

    /**
     * @param $key
     * @return bool
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function forget($key)
    {
        return $this->driver->deleteItem($key);
    }

    /**
     * @param $key
     * @return bool
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function delete($key)
    {
        return $this->forget($key);
    }

    /**
     * @param array $keys
     * @return bool
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function deleteMany(array $keys)
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }

        return true;
    }

    /**
     * @return bool
     */
    public function flush()
    {
        return $this->driver->clear();
    }

    /**
     * @return bool
     */
    public function clear()
    {
        return $this->flush();
    }

    /**
     * @param $key
     * @return bool
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function has($key)
    {
        return $this->driver->getItem($key)->isHit();
    }
}