<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/12
 * Time: 9:32 AM
 */

namespace EasySmartProgram\Support\Cache\Adapter;

use EasySmartProgram\Support\Exception\InvalidConfigException;
use EasySmartProgram\Support\Exception\RuntimeException;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\ChainAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * Class Factory
 * @package EasySmartProgram\Support\Cache
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class Factory
{
    /**
     * @var array
     */
    protected $aliases = [];

    /**
     * @var array
     */
    protected $creators = [];

    /**
     * AdapterFactory constructor.
     * @throws InvalidConfigException
     */
    public function __construct()
    {
        $this->registerDefaultCreator();
    }

    /**
     * @throws InvalidConfigException
     */
    protected function registerDefaultCreator()
    {
        $this->extend($this->filesystemCacheAdapterCreator(), 'filesystem', 'file');
        $this->extend($this->chainCacheAdapterCreator(), 'chain', 'stack');
    }

    /**
     * @param string $name
     * @param array  $config
     * @return AbstractAdapter
     * @throws RuntimeException
     */
    public function make(string $name, array $config = []) : AbstractAdapter
    {
        $creator = $this->aliases[$name] ?? $name;

        if (isset($this->creators[$creator])) {
            return $this->creators[$creator]($config);
        }

        throw new RuntimeException('Can not create cache driver [%s]!');
    }

    /**
     * @param callable    $creator
     * @param string      $name
     * @param string|null $alias
     * @return $this
     * @throws InvalidConfigException
     */
    public function extend(callable $creator, string $name, string $alias = null)
    {
        if (isset($this->creators[$name]) || isset($this->aliases[$alias])) {
            throw new InvalidConfigException(sprintf('Cache driver [%s] already exists!', $name));
        }

        $this->creators[$name] = $creator;
        !empty($alias) && $this->aliases[$alias] = $name;

        return $this;
    }

    /**
     * @param string $name
     * @param string $alias
     * @return $this
     * @throws RuntimeException
     */
    public function alias(string $name, string $alias)
    {
        if (!isset($this->creators[$name])) {
            throw new RuntimeException(sprintf('Creator [%s] not exists!', $name));
        }

        if (isset($this->aliases[$alias])) {
            throw new RuntimeException(sprintf('Creator alias [%s] already exists!', $alias));
        }

        $this->aliases[$alias] = $name;
        return $this;
    }

    /**
     * @return \Closure
     */
    protected function filesystemCacheAdapterCreator()
    {
        return function (array $config = []) {
            $namespace = $config['namespace'] ?? '';
            $lifetime = $config['ttl'] ?? 0;
            $directory = $config['path'] ?? null;
            return new FilesystemAdapter($namespace, $lifetime, $directory);
        };
    }

    /**
     * @return \Closure
     */
    protected function chainCacheAdapterCreator()
    {
        return function (array $config = []) {
            $adapters = [];
            foreach ($config['adapters'] as $adapter) {
                $adapters[] = $this->make($adapter, $config[$adapter] ?? []);
            }

            if (empty($adapters)) {
                throw new InvalidConfigException('Chain adapters can not be empty!');
            }
            return new ChainAdapter($adapters);
        };
    }
}