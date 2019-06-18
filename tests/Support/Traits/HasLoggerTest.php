<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/17
 * Time: 9:03 AM
 */

namespace EasySmartProgram\Tests\Support\Traits;

use EasySmartProgram\Support\Component;
use EasySmartProgram\Support\Log\Logger;
use EasySmartProgram\Support\ServiceContainer;
use EasySmartProgram\Support\Traits\HasLogger;
use Mockery;
use EasySmartProgram\Tests\TestCase;

/**
 * Class HasLoggerTest
 * @package EasySmartProgram\Tests\Support\Traits
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class HasLoggerTest extends TestCase
{
    /**
     * @throws \EasySmartProgram\Support\Exception\InvalidConfigException
     */
    public function testGetLogger()
    {
        $mock = Mockery::mock(HasLogger::class);
        $this->assertInstanceOf(Logger::class, $mock->logger());
    }

    /**
     * @throws \EasySmartProgram\Support\Exception\InvalidConfigException
     */
    public function testGetLoggerFromServiceContainer()
    {
        $client = Mockery::mock(Logger::class);
        $container = Mockery::mock(ServiceContainer::class);
        $container->shouldReceive('offsetExists')->withArgs(['logger'])->andReturnTrue();
        $container->shouldReceive('offSetGet')->withArgs(['logger'])->andReturn($client);

        $component = new Component($container);

        $this->assertInstanceOf(Logger::class, $component->logger());
    }
}