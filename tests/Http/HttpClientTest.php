<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/18
 * Time: 11:32 AM
 */

namespace EasySmartProgram\Tests\Http;

use EasySmartProgram\Http\HttpClient;
use EasySmartProgram\Support\Http\Response;
use EasySmartProgram\Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Mockery;

/**
 * Class HttpClientTest
 * @package EasySmartProgram\Tests\Http
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class HttpClientTest extends TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testRequestWithAccessToken()
    {
        $handlerStack = HandlerStack::create();
        $response = new Response();
        $guzzleClient = Mockery::mock(Client::class);

        $options = [
            'curl' => [
                CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            ],
            'query' => [
                'key' => 'value',
                'access_token' => 'access_token'
            ],
            'handler' => $handlerStack,
        ];

        $guzzleClient->shouldReceive('request')->withArgs(['GET', '/', $options])->andReturn($response);

        $httpClient = (new HttpClient($this->app()))->setGuzzleClient($guzzleClient)->setHandlerStack($handlerStack);

        $this->assertSame($response, $httpClient->withAccessToken()->get('/', ['key' => 'value']));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testRequestWithoutAccessToken()
    {
        $handlerStack = HandlerStack::create();
        $response = new Response();
        $guzzleClient = Mockery::mock(Client::class);

        $options = [
            'curl' => [
                CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            ],
            'query' => [
                'key' => 'value',
            ],
            'handler' => $handlerStack,
        ];

        $guzzleClient->shouldReceive('request')->withArgs(['GET', '/', $options])->andReturn($response);

        $httpClient = (new HttpClient($this->app()))->setGuzzleClient($guzzleClient)->setHandlerStack($handlerStack);

        $this->assertSame($response, $httpClient->withoutAccessToken()->get('/', ['key' => 'value']));
    }
}