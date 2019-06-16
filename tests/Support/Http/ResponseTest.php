<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/16
 * Time: 10:22 AM
 */

namespace EasySmartProgram\Tests\Support\Http;

use EasySmartProgram\Support\Exception\RuntimeException;
use EasySmartProgram\Support\Http\Response;
use EasySmartProgram\Support\Test\TestCase;

/**
 * Class ResponseTest
 * @package EasySmartProgram\Tests\Support\Http
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class ResponseTest extends TestCase
{
    /**
     * @return Response
     */
    public function testFormatJsonResponse()
    {
        $response = new Response(
            200,
            \GuzzleHttp\headers_from_lines(['Content-Type:application/json']),
            '{"name": "easy-smart-program"}'
        );

        $this->assertInstanceOf(\GuzzleHttp\Psr7\Response::class, $response);
        $this->assertSame(['name' => 'easy-smart-program'], $response->array());

        return $response;
    }

    /**
     * @param Response $response
     * @depends testFormatJsonResponse
     */
    public function testArrayAccess(Response $response)
    {
        $this->assertTrue(isset($response['name']));
        $this->assertFalse(isset($response['test_name']));
        $this->assertEquals('easy-smart-program', $response['name']);
        $this->assertNull($response['test_name']);
    }

    /**
     * @param Response $response
     * @depends testFormatJsonResponse
     */
    public function testArrayAccessOffsetSetException(Response $response)
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not allow to set key-value pairs!');
        $response['test_name'] = 'test name';
    }

    /**
     * @param Response $response
     * @depends testFormatJsonResponse
     */
    public function testArrayAccessOffsetUnsetException(Response $response)
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not allow to unset an exists key-value pairs!');
        unset($response['name']);
    }
}