<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/16
 * Time: 3:58 PM
 */

namespace EasySmartProgram\Tests\Support\Http\Middleware;

use EasySmartProgram\Support\Http\Middleware\MiddlewareInterface;
use EasySmartProgram\Support\Http\Middleware\RetryMiddleware;
use EasySmartProgram\Support\Test\HttpClientMiddlewareTestCase;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;

/**
 * Class RetryMiddlewareTest
 * @package EasySmartProgram\Tests\Support\Http\Middleware
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class RetryMiddlewareTest extends HttpClientMiddlewareTestCase
{
    /**
     * @var int
     */
    protected $maxRetries;

    /**
     * @var int
     */
    protected $retryDelay;

    /**
     * @return MiddlewareInterface
     */
    public function middleware(): MiddlewareInterface
    {
        $this->maxRetries = 1;
        $this->retryDelay = 100;
        return new RetryMiddleware(['max_retries' => $this->maxRetries, 'retry_delay' => $this->retryDelay]);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testSuccessRequest()
    {
        $this->appendResponse();
        $response = $this->client()->request('GET', '/');
        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testRetryServerException()
    {
        $this->appendResponse(500)->appendResponse();
        $response = $this->client()->request('GET', '/');
        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testRetryConnectException()
    {
        $this->appendException(new ConnectException('connect error', new Request('GET', '/')));
        $this->appendResponse();

        $response = $this->client()->request('GET', '/');
        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testRetry500Response()
    {
        $this->appendResponse(500)->appendResponse();
        $response = $this->client()->request('GET', '/', ['http_errors' => false]);
        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testRetryOutOfLimit()
    {
        $this->appendResponse(500)->appendResponse(501)->appendResponse();
        $response = $this->client()->request('GET', '/', ['http_errors' => false]);
        $this->assertSame(501, $response->getStatusCode());
    }
}