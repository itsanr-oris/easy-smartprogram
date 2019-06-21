<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/21
 * Time: 4:11 PM
 */

namespace EasySmartProgram\Tests\Support\Http;

use EasySmartProgram\Support\Collection\Collection;
use EasySmartProgram\Support\Http\ResponseHandler;
use EasySmartProgram\Tests\TestCase;
use GuzzleHttp\Psr7\Response;

/**
 * Class ResponseHandlerTest
 * @package EasySmartProgram\Tests\Support\Http
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class ResponseHandlerTest extends TestCase
{
    /**
     * test cast response
     */
    public function testCastResponse()
    {
        $handler = new ResponseHandler();
        $data = ['key' => 'value'];
        $response = new Response(200, ['Content-Type' => 'application/json'], json_encode($data));

        $this->assertEquals($data, $handler->castResponse($response, ResponseHandler::TYPE_ARRAY));
        $this->assertEquals(new Collection($data), $handler->castResponse($response, ResponseHandler::TYPE_COLLECTION));
        $this->assertEquals($response, $handler->castResponse($response, ResponseHandler::TYPE_GUZZLE_RESPONSE));

        $response = new Response(200, ['Content-Type' => 'text/html; charset=utf-8'], 'plain text');
        $this->assertEquals('plain text', $handler->castResponse($response, ResponseHandler::TYPE_ARRAY));
    }
}