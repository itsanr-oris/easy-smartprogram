<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/16
 * Time: 3:54 PM
 */

namespace EasySmartProgram\Tests\Support\Http\Middleware;

use EasySmartProgram\Support\Http\Middleware\MiddlewareInterface;
use EasySmartProgram\Support\Http\Middleware\ResponseMiddleware;
use EasySmartProgram\Support\Http\Response;
use EasySmartProgram\Support\Test\HttpClientMiddlewareTestCase;

/**
 * Class ResponseMiddlewareTest
 * @package EasySmartProgram\Tests\Support\Http\Middleware
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class ResponseMiddlewareTest extends HttpClientMiddlewareTestCase
{
    /**
     * @return MiddlewareInterface
     */
    public function middleware(): MiddlewareInterface
    {
        return new ResponseMiddleware();
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testResponseMiddleware()
    {
        $response = $this->appendResponse()->client()->request('GET', '/');

        $this->assertInstanceOf(Response::class, $response);
    }
}