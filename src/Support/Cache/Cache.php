<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/12
 * Time: 9:28 AM
 */

namespace EasySmartProgram\Support\Cache;

use EasySmartProgram\Support\Cache\Adapter\Factory as AdapterFactory;

/**
 * Class CacheManager
 * @package EasySmartProgram\Support\Cache
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class Cache
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var AdapterFactory
     */
    protected $adapterFactory;

    /**
     * @var
     */
    protected $adapter;

    /**
     * CacheManager constructor.
     * @param AdapterFactory $factory
     * @param array          $config
     */
    public function __construct(AdapterFactory $factory, array $config = [])
    {
        $this->config = $config;
        $this->setAdapterFactory($factory);
    }

    /**
     * @param AdapterFactory $factory
     * @return Cache
     */
    public function setAdapterFactory(AdapterFactory $factory)
    {
        $this->adapterFactory = $factory;
        return $this;
    }

    /**
     * @return AdapterFactory
     */
    public function getAdapterFactory() : AdapterFactory
    {
        return $this->adapterFactory;
    }

    /**
     * @param $adapter
     * @return Cache
     */
    public function adapter(string $adapter)
    {
        return $this->setAdapter($adapter);
    }

    /**
     * @param $adapter
     * @return Cache
     */
    public function setAdapter(string $adapter)
    {
        $this->config['default'] = $adapter;
        $this->adapter = null;
        return $this;
    }

    /**
     * @return \Symfony\Component\Cache\Adapter\AbstractAdapter
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     */
    public function getAdapter()
    {
        if ($this->adapter === null) {
            $adapter = $this->config['default'] ?? 'file';
            $config = $this->config['drivers'][$adapter] ?? [];
            $config['life_time'] = $this->config['life_time'] ?? 3600;
            $this->adapter = $this->getAdapterFactory()->make($adapter, $config);
        }

        return $this->adapter;
    }

    /**
     * @param $id
     * @param $value
     * @return Cache
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     */
    public function set($id, $value)
    {
        $adapter = $this->getAdapter();

        $item = $adapter->getItem($id)->set($value);
        $adapter->save($item);

        return $this;
    }

    /**
     * @param $id
     * @return null
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     */
    public function get($id)
    {
        $adapter = $this->getAdapter();

        $item = $adapter->getItem($id);
        return $item->isHit() ? $item->get() : null;
    }

    /**
     * @param $id
     * @return Cache
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     */
    public function delete($id)
    {
        $this->getAdapter()->deleteItem($id);
        return $this;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     */
    public function has($id)
    {
        return $this->getAdapter()->getItem($id)->isHit();
    }
}