<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/17
 * Time: 11:31 AM
 */

namespace EasySmartProgram\Tests\Support\Cache;

use EasySmartProgram\Support\Cache\Adapter\Factory;
use EasySmartProgram\Support\Cache\Cache;
use EasySmartProgram\Tests\TestCase;
use Mockery;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\MemcachedAdapter;

/**
 * Class CacheTest
 * @package EasySmartProgram\Tests\Support\Cache
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class CacheTest extends TestCase
{
    /**
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     */
    public function testGetDefaultCacheDriver()
    {
        $factory = Mockery::mock(Factory::class);

        $filesystemAdapter = Mockery::mock(FilesystemAdapter::class);
        $factory->shouldReceive('make')->withArgs(['file', ['life_time' => 3600]])->andReturn($filesystemAdapter);

        $cache = new Cache($factory, []);
        $this->assertInstanceOf(FilesystemAdapter::class, $cache->getAdapter());
    }

    /**
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     */
    public function testConfigDefaultCacheDriver()
    {
        $factory = Mockery::mock(Factory::class);

        $filesystemAdapter = Mockery::mock(FilesystemAdapter::class);
        $factory->shouldReceive('make')->withArgs(['file', ['life_time' => 3600]])->andReturn($filesystemAdapter);

        $memcachedAdapter = Mockery::mock(MemcachedAdapter::class);
        $factory->shouldReceive('make')->withArgs(
            [
                'memcached',
                [
                    'dsn' => ['memcached://localhost:11211'],
                    'life_time' => 3600
                ],
            ]
        )->andReturn($memcachedAdapter);

        $cache = new Cache($factory, [
            'default' => 'memcached',
            'drivers' => [
                'file' => [],
                'memcached' => [
                    'dsn' => ['memcached://localhost:11211']
                ]
            ],
        ]);

        $this->assertInstanceOf(MemcachedAdapter::class, $cache->getAdapter());

        return $cache;
    }

    /**
     * @depends testConfigDefaultCacheDriver
     * @param Cache $cache
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     */
    public function testSpecificCacheDriver(Cache $cache)
    {
        $this->assertInstanceOf(FilesystemAdapter::class, $cache->adapter('file')->getAdapter());
    }

    /**
     * @throws \EasySmartProgram\Support\Exception\InvalidConfigException
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     */
    public function testSetCacheData()
    {
        $cache = new Cache(new Factory(), [
            'default' => 'file',
            'drivers' => [
                'file' => ['path' => sys_get_temp_dir() . '/cache/'],
            ]
        ]);
        $this->assertSame($cache, $cache->set('test_cache_id', 'cache_value'));
        $this->assertTrue($cache->getAdapter()->getItem('test_cache_id')->isHit());

        return $cache;
    }

    /**
     * @param Cache $cache
     * @depends testSetCacheData
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     */
    public function testGetCacheData(Cache $cache)
    {
        $this->assertSame('cache_value', $cache->get('test_cache_id'));
        $this->assertNull($cache->get('not_exists_cache_key'));
    }

    /**
     * @param Cache $cache
     * @depends testSetCacheData
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     */
    public function testHasCacheData(Cache $cache)
    {
        $this->assertTrue($cache->has('test_cache_id'));
    }

    /**
     * @param Cache $cache
     * @depends testSetCacheData
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     */
    public function testDeleteCacheData(Cache $cache)
    {
        $this->assertSame($cache, $cache->delete('test_cache_id'));
        $this->assertFalse($cache->getAdapter()->getItem('test_cache_id')->isHit());
    }

    /**
     * @param Cache $cache
     * @depends testSetCacheData
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     */
    public function testSetDuplicateCacheId(Cache $cache)
    {
        $cache->set('test_cache_id', 'cache_value_2');
        $this->assertSame('cache_value_2', $cache->get('test_cache_id'));
    }
}