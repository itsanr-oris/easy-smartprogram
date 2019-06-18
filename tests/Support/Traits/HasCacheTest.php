<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/17
 * Time: 8:55 AM
 */

namespace EasySmartProgram\Tests\Support\Traits;

use EasySmartProgram\Support\Cache\Cache;
use EasySmartProgram\Support\Component;
use EasySmartProgram\Support\ServiceContainer;
use Mockery;
use EasySmartProgram\Support\Traits\HasCache;
use EasySmartProgram\Tests\TestCase;

/**
 * Class HasCacheTest
 * @package EasySmartProgram\Tests\Support\Traits
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class HasCacheTest extends TestCase
{
    /**
     * @throws \EasySmartProgram\Support\Exception\InvalidConfigException
     */
    public function testGetCache()
    {
        $mock = Mockery::mock(HasCache::class);
        $this->assertInstanceOf(Cache::class, $mock->cache());
    }

    /**
     * @throws \EasySmartProgram\Support\Exception\InvalidConfigException
     */
    public function testGetCacheFromServiceContainer()
    {
        $cache = Mockery::mock(Cache::class);
        $container = Mockery::mock(ServiceContainer::class);
        $container->shouldReceive('offsetExists')->withArgs(['cache'])->andReturnTrue();
        $container->shouldReceive('offSetGet')->withArgs(['cache'])->andReturn($cache);

        $component = new Component($container);

        $this->assertInstanceOf(Cache::class, $component->cache());
    }
}