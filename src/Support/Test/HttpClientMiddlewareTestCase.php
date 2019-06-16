<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/16
 * Time: 2:37 PM
 */

namespace EasySmartProgram\Support\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use EasySmartProgram\Support\Http\Middleware\MiddlewareInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

abstract class HttpClientMiddlewareTestCase extends TestCase
{
    /**
     * @var MockHandler
     */
    protected $mockHandler;

    /**
     * set up
     */
    public function setUp() : void
    {
        parent::setUp();
        $this->mockHandler = new MockHandler();
    }

    /**
     * @param int $code
     * @param array $headers
     * @param string $body
     * @param string $version
     * @param string|null $reason
     * @return $this
     */
    protected function appendResponse(
        int $code = 200,
        array $headers = [],
        string $body = '',
        string $version = '1.1',
        string $reason = null
    ) {
        $this->mockHandler->append(new Response($code, $headers, $body, $version, $reason));
        return $this;
    }

    /**
     * @param \Exception $exception
     */
    protected function appendException(\Exception $exception)
    {
        $this->mockHandler->append($exception);
        return $this;
    }

    /**
     * @return Client
     */
    public function client()
    {
        $stack = HandlerStack::create($this->mockHandler);
        $middleware = $this->middleware();
        $stack->unshift($middleware->callable(), $middleware->name());

        return new Client(['handler' => $stack]);
    }

    /**
     * @return MiddlewareInterface
     */
    abstract public function middleware() : MiddlewareInterface;
}