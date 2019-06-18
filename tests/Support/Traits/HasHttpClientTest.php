<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/17
 * Time: 9:01 AM
 */

namespace EasySmartProgram\Tests\Support\Traits;

use EasySmartProgram\Support\Component;
use EasySmartProgram\Support\Http\HttpClient;
use EasySmartProgram\Support\ServiceContainer;
use EasySmartProgram\Support\Traits\HasHttpClient;
use EasySmartProgram\Tests\TestCase;
use Mockery;

/**
 * Class HasHttpClientTest
 * @package EasySmartProgram\Tests\Support\Traits
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class HasHttpClientTest extends TestCase
{
    /**
     * test get http client
     */
    public function testGetHttpClient()
    {
        $mock = Mockery::mock(HasHttpClient::class);
        $this->assertInstanceOf(HttpClient::class, $mock->http());
    }

    /**
     * test get http client from service container
     */
    public function testGetHttpClientFromServiceContainer()
    {
        $client = Mockery::mock(HttpClient::class);
        $container = Mockery::mock(ServiceContainer::class);
        $container->shouldReceive('offsetExists')->withArgs(['http_client'])->andReturnTrue();
        $container->shouldReceive('offSetGet')->withArgs(['http_client'])->andReturn($client);

        $component = new Component($container);

        $this->assertInstanceOf(HttpClient::class, $component->http());
    }
}